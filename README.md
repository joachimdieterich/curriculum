curriculum BETA Version 0.9.3

Version 0.9.3
=================
- Bootstrap Layout (AdminLTE 2)
- Input-Forms von Views abgekoppelt (Input Forms werden jetzt immer in Modals aufgerufen)
- Modals können nun per JS von jedem Punkt aus aufgerufen werden.
- Neu: Form Processors
- Progressbar beim Upload
- Font Awesome 4.6.1

Bugfixes:
    - Std. Profilbild wurde beim importieren falsch gesetzt
Version 0.9.2
=================
- Pinnwand (Dashboard) 
- Dateizugriff abgesichert (session)
- Seitenzähler an Paginatoren optimiert (function.paginate_middle.php)
- XML Import / Export von Lehrplänen
- https wird unterstützt

- div. Bugfixes
    - FIXED: 
    - Einschreibungen werden vor Löschung von Lehrplänen überprüft, nicht mehr benötigte Dateien die mit dem Lehrplan verknüpft waren werden gelöscht.

Version 0.9.1
=================

curriculum is a learning platform where teachers can create topic-based learning objectives.
The resulting curricula can be linked with learning groups and be viewed by learning group members. 
This will give students, teachers (and parents) a good overview of the objectives to be achieved. 
Not yet reached objectives are shown in red - if a objective is achieved, it will be shown in green or orange (if achieved with help). 
So curriculum provides a good overview of the current learning status. 
More information at http://www.joachimdieterich.de

Copyright notice
Copyright © 2012 onwards Joachim Dieterich (http://www.joachimdieterich.de)
This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License as published by  
the Free Software Foundation; either version 3 of the License, or (at your option) any later version.                                   

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of        
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details: http://www.gnu.org/copyleft/gpl.html 

required librarys
=================
- php5-gd       : to generate Thumbnails