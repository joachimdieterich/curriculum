# Contribution guide for curriculum

Curriculum follows the standard Github contribution model: 

0. fork a project
1. clone your fork
2. change the code 
3. push your changes to the forked repository 
4. send a pull request

 See https://help.github.com/articles/fork-a-repo/ if you have no idea what we
 are talking about.

## Getting started

### Setup GIT

0. Download GIT for your platform
1. Install GIT
2. <code>git config --global user.name "Your Name"</code>
3. <code>git config --global user.email your@name.some.where</code>
4. <code>git config --global color.ui auto</code>

### Create your own fork

Create your own fork of the curriculum repository but add the
original repository so changes might be pulled down.

0. Create you own github account 
1. fork curriculum repository https://github.com/joachimdieterich/curriculum.git
2. <code>git clone https://github.com/&lt;your_user_name&gt;/curriculum.git</code>
3. <code>cd curriculum</code>
4. <code>git remote add upstream https://github.com/joachimdieterich/curriculum.git</code>
5. <code>git fetch upstream</code>

## Common tasks

### Update from upstream repository and push to your own fork

Anytime you want to merge in the latest changes from the curriculum upstream
repository, just issue 

    git pull upstream master

and push them to your own fork with

    git push


### Make sure you have the latest version

1. <code>git checkout master</code>
2. <code>git pull</code>
3. <code>git pull upstream master</code>

### Make your own branch &lt;issue&gt;

    git checkout -b <issue>

### commit changes

1. <code>git add blah/blub.php</code>
2. <code>git commit -m "blah blub"</code>

### push to forked repository

    git push origin --set-upstream +<issue>

### Send pull Request

Use web frontend on github

### Drop forked branch

1. <code>git checkout master</code>
2. <code>git pull upstream master</code>
3. <code>git push</code>
4. <code>git branch -D &lt;issue&gt;</code>
5. <code>git push origin --delete &lt;issue&gt;</code>
