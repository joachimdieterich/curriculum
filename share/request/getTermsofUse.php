<?php
/** This file is part of curriculum - http://www.joachimdieterich.de
* 
* @package core
* @filename getTermsofUse.php
* @copyright 2015 Joachim Dieterich
* @author Joachim Dieterich
* @date 2015.12.16 09:01
* @license: 
*
* The MIT License (MIT)
* Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
* to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
* and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
* DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
* THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
$base_url               = dirname(__FILE__).'/../';
include($base_url.'config.php');  //Läd Klassen, DB Zugriff und Funktio
echo'<html lang="de" class="no-js">
     <head>
        <title>Zustimmungserklärung | '.$CFG->app_title.'</title>
        <meta charset="utf-8"> 
        <link rel="stylesheet" href="../../public/assets/templates/AdminLTE-2.3.0/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="../../share/libs/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="../../public/assets/stylesheets/google-fonts.css" >
        <link rel="stylesheet" href="../../public/assets/templates/AdminLTE-2.3.0/dist/css/AdminLTE.min.css">
        <link rel="stylesheet" href="../../public/assets/templates/AdminLTE-2.3.0/dist/css/skins/_all-skins.min.css">
     </head>
     <body class="hold-transition skin-blue sidebar-mini sidebar-collapse" data-spy="scroll" data-target=".modal-body">
        <div class="wrapper">
        <header class="main-header"></header> 
            <div id="content-wrapper" class="content-wrapper">
            <section class="content">
            <section class="content-header"><h1>Zustimmungserklärung</h1></section>
                <div class="row ">
                    <div class="col-xs-12">
                        <h4 >Lesen Sie diese Zustimmungserklärung sorgfältig. Sie müssen erst zustimmen, um diese Webseite nutzen zu können. Stimmen Sie zu?</h4>
                        <form action="../../public/index.php?action=login" method="post"><input name="terms" type="hidden" value="terms" />
                        <div class="btn-group" role="group" aria-label="...">
                            <button type="submit" class="btn btn-default" name="Submit" value="Ja" >
                                 <span class="fa fa-thumbs-o-up text-green"></span> Ja, ich stimme zu.
                            </button>
                            <button type="submit" class="btn btn-default" name="Submit" value="Nein" >
                                <span class="fa fa-thumbs-o-down text-red"></span> Nein, ich stimme nicht zu.
                            </button>
                        </div><br><br>
                        <div id="pdf_Terms" style="width:100%; height: 600px;"></div>
                        <script src="../../public/assets/scripts/PDFObject-master/pdfobject.min.js"></script> 
                        <script>PDFObject.embed("../../public/assets/docs/curriculum_Terms_Of_Use_2015.pdf", "#pdf_Terms");</script>
                        </form>
                        
                    </div>
                </div>
             </div>
           </section>
           </div>
        </div>
     </body>
     </html>';