curriculum BETA Version 0.9.3

Version 0.9.3
=================
- Self Assessment for students
- Bootstrap Layout (AdminLTE 2)
- Input-Forms von Views abgekoppelt (Input Forms werden jetzt immer in Modals aufgerufen)
- Modals können jetzt per JS von jedem Punkt aus aufgerufen werden.
- Neu: Form Processors unter /share/processors
- Progressbar beim Upload
- Font Awesome 4.7.0
- div. Bugfixes
    
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



The MIT License (MIT)
Copyright (c) 2012 Joachim Dieterich http://www.curriculumonline.de

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF 
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR 
THE USE OR OTHER DEALINGS IN THE SOFTWARE.

required librarys
=================
- php5-gd       : to generate Thumbnails