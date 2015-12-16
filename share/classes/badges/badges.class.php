<?php
/**
 * This file is part of curriculum - http://www.joachimdieterich.de
 * 
 * @package core
 * @filename badge.class.php
 * @copyright 2015 joachimdieterich
 * @author joachimdieterich
 * @date 2015.04.01 10:36
 * @license: 
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
 * the Free Software Foundation; either version 3 of the License, or (at your option) any later version. 
 *                                                                       
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details: 
 *                                                                       
 * http://www.gnu.org/copyleft/gpl.html      
 */

class Badges {  
    /**
     * ID from database entry
     * @var integer 
     */
    public $id; 
    
    /**
     * Used to identify badge in API endpoints (auto-generated)
     * @var string 
     */
    public $slug;
    
    /**
     * Display name
     * @var string 
     */
    public $name; 
    
    /**
     * Short tagline description
     * @var string 
     */
    public $strapline; 
    
    /**
     * Description for potential earners
     * @var string 
     */
    public $earnerDescription;
    
    /**
     * Description for viewers of badge e.g. college admin or employer
     * @var string 
     */
    public $consumerDescription;
    
    /**
     *
     * @var string 
     */
    public $issuerUrl;
    
    /**
     * Link to supporting material
     * @var string 
     */
    public $rubricUrl;
    
    /**
     * Time estimate for earner to complete badge
     * @var integer
     */
    public $timeValue; 
    
    /**
     * Can be minutes, hours, days or weeks
     * @var enum 
     */
    public $timeUnits;
    
    /**
     * Can be URL, Text, Photo, Video or Sound
     * @var enum 
     */
    public $evidenceType;
    
    /**
     * Limit for number of people who can earn the badge
     * @var integer
     */
    public $limit;
    
    /**
     * True if the same earner can only earn the badge once
     * @var boolean
     */
    public $unique;
    
    /**
     *
     * @var timestamp
     */
    public $created; 
    
    /**
     * Badge display image
     * @var string
     */
    public $imageUrl;
    
    /**
     * Badges can be organized by type and category
     * @var string
     */
    public $type; 
    
    /**
     * Archived badges can no longer be earned
     * @var boolean
     */
    public $archived;
    
    /**
     * System is represented by ID in database - system details are returned from API endpoints as nested JSON
     * @var integer
     */
    public $system;
    
    /**
     * Isuser is represented by ID in database - issuer details are returned from API endpoints as nested JSON
     * @var integer
     */
    public $issuer; 
    
    /**
     * Program is represented by ID in database - program details are returned from API endpoints as nested JSON
     * @var integer
     */
    public $program; 
    
    /**
     * Link to criteria material
     * @var string
     */
    public $criteriaUrl; 
    
    /**
     * Each item includes id, description, required status and note.
     * @var array
     */
    public $criteria;
    
    /**
     * See above for related type field
     * @var array
     */
    public $categories; 
    
    /**
     * Tags can be used to aid search and discovery of badges
     * @var array
     */
    public $tags; 
    
    /**
     * A milestone badge is awarded when a set of other badges is earned.
     * @var array
     */
    public $milestones; 
    
    
    public function add($ter_id, $ena_id, $criteria){
        $con = new BadgekitConnection();
        $data = json_encode($this);
        $badge = $con->createBadge('badgekit', $data);
        $db = DB::prepare('INSERT INTO badges (badge_slug,ter_id,ena_id,criteria) VALUES (?,?,?,?)');
        
        return $db->execute(array($badge->badge->slug, $ter_id, $ena_id, $criteria));
    }
    
    public function getBadgeSlug($ter_id, $ena_id=0){
        $db = DB::prepare('SELECT badge_slug FROM badges WHERE ter_id = ? AND ena_id = ?');
        $db->execute(array($ter_id, $ena_id));  
        $result = $db->fetchObject();
        if (is_object($result)){
            return $result->badge_slug;
        } else {return false;}
    }
    
    public function getCriteria($ter_id, $ena_id){
        $db = DB::prepare('SELECT criteria FROM badges WHERE ter_id = ? AND ena_id = ?');
        $db->execute(array($ter_id, $ena_id));
        $result = $db->fetchObject();
        if (is_object($result)){
            return $result->criteria;
        } else {return false;}
    }
}