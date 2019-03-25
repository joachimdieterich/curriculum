<?php

/*
 * The MIT License
 *
 * Copyright 2018 joachimdieterich.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


class repository_plugin_edusharing extends repository_plugin_base {
        const PLUGINNAME = 'edusharing';
	private $accessToken = '';
	private $repoUrl;
	private $repoUser;
	private $repoPwd;
	public function __construct() {
            global $USER;
		$this->repoUrl  = $this->config('repoUrl');
		$this->repoUser = $this->config('repoUser');
		$this->repoPwd  = $this->config('repoPwd');
                if (isset($USER->id)){
                    $this->setTokens(); //do not set token here to prevent blankpage if edusharing is offline
                }
	}
        
        private function config($name){
        $db = DB::prepare('SELECT value FROM config_plugins WHERE plugin = ? AND name = ?');
        $db->execute(array('repository/edusharing', $name));
        $result = $db->fetchObject();
        if ($result){
            return $result->value;
        }   
    }
        
	private function setTokens() {
		$postFields = 'grant_type=password&client_id=eduApp&client_secret=secret&username=' . $this->repoUser . '&password=' . $this->repoPwd;
		$raw = $this->call ( $this->repoUrl . '/oauth2/token', 'POST', array (), $postFields );
		$return = json_decode ( $raw );
		$this->accessToken = $return->access_token;
                //error_log($this->accessToken);
	}
        public function getAbout() {
		$ret = $this->call($this->repoUrl . 'rest/_about');
		return json_decode($ret, true);
	} 
        
	public function createUser($user) {
		$this->call ( $this->repoUrl . '/rest/iam/v1/people/-home-/' . urlencode ( $user ['username'] ), 'POST', array (
				'Content-Type: application/json' 
		), json_encode ( $user ['profile'] ) );
	}
	public function setCredential($user) {
		$this->call ( $this->repoUrl . '/rest/iam/v1/people/-home-/' . urlencode ( $user ['username'] ) . '/credential', 'PUT', array ('Content-Type: application/json' ), json_encode ( array (
				'newPassword' => $user ['password'] 
		) ) );
	}
	public function getGroup($group) {
		return json_decode($this->call ( $this->repoUrl . '/rest/iam/v1/groups/-home-/' . urlencode ( $group ) ), true);
	}
	public function createGroup($group) {
		$this->call ( $this->repoUrl . '/rest/iam/v1/groups/-home-/' . urlencode ( $group ['groupname'] ), 'POST', array (
				'Content-Type: application/json' 
		), json_encode ( $group ['properties'] ) );
	}
	public function addMember($group, $member) {
		$this->call ( $this->repoUrl . '/rest/iam/v1/groups/-home-/' . urlencode ( $group ) . '/members/' . urlencode ( $member ), 'PUT' );
	}
	public function getUser($username = '-me-') {
		return $this->call ( $this->repoUrl . '/rest/iam/v1/people/-home-/' . urlencode ( $username ) );
	}
	public function createIoNode($title, $folderId) {
		$postFields = array (
				array (
						'name' => '{http://www.alfresco.org/model/content/1.0}name',
						'values' => array (
								$title 
						) 
				) 
		);
		return $this->call ( $this->repoUrl . '/rest/node/v1/nodes/-home-/' . urlencode ( $folderId ) . '/children?type=%7Bhttp%3A%2F%2Fwww.campuscontent.de%2Fmodel%2F1.0%7Dio', 'POST', array (
				'Content-Type: application/json' 
		), json_encode ( $postFields ) );
	}
	public function addNodeContent($nodeId, $versionComment, $mimetype, $postFields) {
		return $this->call ( $this->repoUrl . '/rest/node/v1/nodes/-home-/' . urlencode ( $nodeId ) . '/content?versionComment=' . urlencode ( $versionComment ) . '&mimetype=' . urlencode ( $mimetype ), 'POST',
				array ('Content-Type: multipart/form-data'), $postFields  );
	}
	public function setPermissions($nodeId, $permissions) {
		$postFields = array (
				'inherited' => false,
				'permissions' => $permissions 
		);
		$this->call ( $this->repoUrl . '/rest/node/v1/nodes/-home-/' . $nodeId . '/permissions', 'PUT', array ('Content-Type: application/json' ), json_encode ( $postFields ) );
	}
	
	public function createFolder($title, $parentId) {
		$postFields = array (
				array (
						'name' => '{http://www.alfresco.org/model/content/1.0}name',
						'values' => array (
								$title
						)
				)
		);
		$node = $this->call ( $this->repoUrl . '/rest/node/v1/nodes/-home-/' . urlencode ( $parentId ) . '/children?type=' . urlencode('{http://www.campuscontent.de/model/1.0}map'), 'POST', array (
				'Content-Type: application/json'
		), json_encode ( $postFields ) );
		$return = json_decode ( $node );
		return $return->node->ref->id;
	}
	
	public function setOrganization($organization, $folderId) {
		$this->call($this->repoUrl . '/rest/organization/v1/organizations/-home-/'.$organization . '?folder=' . $folderId, 'PUT', array(), json_encode(array('organization'=>$organization, 'folder' => $folderId)) );
	}
	
	public function getCompanyHome() {
		$return = $this->call($this->repoUrl . '/rest/node/v1/nodes/-home-?query=' . urlencode('PATH:"/app:company_home"'));
		$return = json_decode($return, true);
		return $return['nodes'][0]['ref']['id'];
	}
	
	public function searchNodes($searchString) {
		$return = $this->call($this->repoUrl . '/rest/node/v1/nodes/-home-?query=' . urlencode($searchString));
		return json_decode($return, true);
	}
	
	public function getPerson($person = '-me-') {
		$return = $this->call($this->repoUrl . '/rest/iam/v1/people/-home-/' . $person);
		$return = json_decode($return, true);
	
	}
	
	public function getChildren($parentId) {
		$children = $this->call($this->repoUrl . '/rest/node/v1/nodes/-home-/'.$parentId.'/children?maxItems=5000000&skipCount=0');
		return json_decode($children, true);
	}
	
	public function getAllGroups() {
		$ret = $this->call($this->repoUrl . '/rest/iam/v1/groups/-home-?pattern=*');
		return json_decode($ret, true);
	}
	
	public function getOrganizations() {
		$ret = $this->call($this->repoUrl . '/rest/organization/v1/organizations/-home-');
		return json_decode($ret, true);
	}
	
	private function call($url, $httpMethod = '', $additionalHeaders = array(), $postFields = array()) {   
            $ch = curl_init ();
            curl_setopt ( $ch, CURLOPT_URL, $url );
            curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );

            switch ($httpMethod) {
                    case 'POST' :
                            curl_setopt ( $ch, CURLOPT_POST, true );
                            break;
                    case 'PUT' :
                            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                            break;
                    case 'DELETE' :
                            curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
                            break;
                    default :
            }

            $headers = array_merge ( array (
                            'Accept: application/json',
                            'Authorization: Bearer ' . $this->accessToken 
            ), $additionalHeaders );
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );

            if (! empty ( $postFields )) {
                    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $postFields );
            }

            $exec = curl_exec ( $ch );

            if ($exec === false) {
                    throw new Exception ( curl_error ( $ch ) );
            }

            $httpcode = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
            if ($httpcode != 200) {
                    //deal with it
            }
            curl_close ( $ch );
            return $exec;
	}
        
        public function getCollections($repository, $collection) {
            $getFields = array (
                            'repository' => $repository,
                            'collection' => $collection 
            );
            $ret =$this->call ( $this->repoUrl . '/rest/collection/v1/collection/' . $repository . '/permissions', 'GET', array ('Content-Type: application/json' ), json_encode ( $getFields ) );
	}
        
        public function getRendering($repository, $node) {
            $query = $this->repoUrl . 'rest/rendering/v1/details/' . $repository . '/' . $node;
            //error_log($query);
            $ret = $this->call($this->repoUrl . 'rest/rendering/v1/details/' . $repository . '/' . $node);
            return json_decode($ret, true);
	}
        
        //https://mediathek.schul.campus-rlp.de/edu-sharing/swagger/#!/SEARCH_v1/searchByProperty
        public function getSearchCustom($repository, $params) {
            //error_log($this->repoUrl . 'rest/search/v1/custom/' . $repository.'?'.http_build_query($params));
            $ret =$this->call ( $this->repoUrl . 'rest/search/v1/custom/' . $repository.'?'.http_build_query($params));
            //error_log($this->repoUrl . 'rest/search/v1/custom/' . $repository.'?'.http_build_query($params));
            //error_log(json_encode($ret));
            return json_decode($ret, true);
	}
        
        public function getFiles ($dependency, $id, $files){
            $db         = DB::prepare('SELECT fi.* FROM files AS fi, file_subscriptions AS fs 
                                WHERE fs.reference_id = ? AND fs.context_id = ? 
                                AND fi.id = fs.file_id AND fi.orgin = ?');   
            $db->execute(array($id, $_SESSION['CONTEXT'][$dependency]->id, self::PLUGINNAME));
            $es_array   = array();
            error_log('counter'.json_encode($result));
            while($result = $db->fetchObject()) { 
                $es_array = array_merge($es_array, $this->processReference($result->path));
            }

            if (is_array($files) AND !empty($es_array[0])){ // beide Vorhanden
                $files = array_merge($files, $es_array);
            } else if (!empty($es_array[0])) { // nur Omega vorhanden
                $files = $es_array;
            }   
            
            return $files;
        }
        
        public function processReference($arguments){
            parse_str($arguments, $query);
            $contentType    = $query['contentType'];    //e.g.'FILES';
            $property       = $query['property'];      //e.g.'ccm:competence_digital2';
            $value          = $query['value'];          //e.g.11990503;
            $maxItems       = 10;
            $skipCount      = 0;
            //error_log(json_encode($arguments));
            $this->setTokens(); //reset token
            //$nodes        = $this->getSearchCustom('-home-', array ('contentType' =>'FILES', 'property' => 'ccm:competence_digital2', 'value' => '11061007', 'maxItems' => 10));
            $nodes      = $this->getSearchCustom('-home-', array ('contentType' =>$contentType, 'property' => $property, 'value' => $value, 'maxItems' => $maxItems, 'skipCount' => $skipCount));
            //error_log(json_encode($nodes));
            $tmp_file   = new File();
            $tmp_array  = array();
            foreach ($nodes['nodes'] as $node) {
                //error_log('geht doch'.json_encode($node['preview']['url']));
                $tmp_file->license      = $node['licenseURL'];
                $tmp_file->title        = $node['title'];
                $tmp_file->type         ='external';
                $tmp_file->file_context = 5; //--> todo define context!
                $tmp_file->description  = $node['description'];
                $tmp_file->file_version['t']['filename'] = $node['preview']['url'];
                $tmp_file->filename     = $node['contentUrl'];
                $tmp_file->path         = 'https://hochschul.campus-rlp.de/edu-sharing/components/render/'.$node['ref']['id'];
                //$tmp_file->path         = $node['contentUrl'];
                $tmp_file->full_path    = 'https://hochschul.campus-rlp.de/edu-sharing/components/render/'.$node['ref']['id'];
                //error_log($tmp_file->full_path);
                //$tmp_file->full_path    = $node['contentUrl'];
                $tmp_file->orgin        = self::PLUGINNAME;
                $tmp_array[]            = clone $tmp_file;     
            }
            //error_log(json_encode($tmp_array));
            return $tmp_array;
        }
        
        /**
         * Count Links bases on type and reference_id
         * @param string $type
         * @param int $id
         * @return int matches
         */
        public function count($type, $id){
            $db         = DB::prepare('SELECT count(fi.id) as MAX FROM files AS fi, file_subscriptions AS fs WHERE fi.orgin = ? AND fi.id = fs.file_id AND fs.context_id = ? AND fs.reference_id = ?');
            $db->execute(array('edusharing', $_SESSION['CONTEXT'][$type]->id, $id ));
            $res        = $db->fetchObject();
            if ($res->MAX >= 1){
                return $res->MAX;
            } else {
                return 0;
            }
        }
        
        public function render($file){
            $c  = '';        
            $c .=  '<div class="info-box">
                        <span class="info-box-icon bg-aqua"><img src="'.$file->file_version['t']['filename'].'" alt="" style="display: block; object-fit: cover;"></span>
                        <div class="info-box-content">
                          <span class="info-box-text">'.$file->title.'</span>
                          <span class="info-box-number"><small>'.$file->description.'</small></span>
                        </div>
                        <!-- /.info-box-content -->
                     </div>';
            return $c;
        }
        
        public function set_link_to_curriculum_db($context_id, $reference_id, $content_type, $propery, $value, $file_context, $file_context_reference_id){
            //todo: check capability
            // Add Entry to file table
            $f               = new File();
            $f->context_id   = $context_id; 
            $f->reference_id = $reference_id;
            $f->path         = "contentType={$content_type}&property={$propery}&value={$value}";
            $f->filename     = '';
            $f->author       = '';
            $f->license      = '';
            $f->file_context = $file_context;
            $f->orgin        = 'edusharing';
            $file_id         = $f->add();
            
            $f->subscribe($file_id, $context_id, $reference_id, $file_context, $file_context_reference_id);
            
        }
        
}
