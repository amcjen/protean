OVERVIEW
=========
Protean is a PHP5 framework that I began developing in 2005 while flying back from a Debian Linux conference held in Helsinki Finland.  Something about the altitude triggered me to realize there must be an easier, better way to deploy PHP5 projects quickly, safely, while leveraging as many existing top-notch packages such as Smarty, PropelORM, PHPUnit, Phing, and others.  

I took the initial base ideas of the framework from Matt Zandstra (now at Yahoo!), who wrote an excellent book on making clean, testable PHP5 code.  I had additional requirements, such as one-line testability, support for continuous integration, multi-lingual support, and easy templating for front-end developers.


HOW IT WORKS:
============
It's quite simple at it's core--Protean is an MVC-based, inversion-of-control framework.  There are several modules. Each module can live alone or with others. Each is self-contained, meaning it has all information within it to handle all routing of controllers, view template rendering, and model support--which includes both ORM and domain-object mapping.

*Note* - The simplicity of the examples here hopefully do not imply a simplicity or lack of power of PHP5 or of the Protean framework itself. Despite what some say, PHP is a very capable and useful platform on which to build complex, highly-scalable web applications.  There is some pretty cool black magic going on in the internals of Protean to automatically show the proper templates, call the proper controller class, auto-load the domain/ORM object classes, as well as instantiate the page flow.  PHP5's Reflection classes and variable variables are used to great extent to allow the code a development team has to write day in and day out to be as simple as shown in the examples here.

How Protean routes: command.xml
-------------------------------
Very simply!  Here is a simple route that exposes the /content/hello URI.  The <command> name attribute defines what controller is called.  The view tags describe what header/footer and body views will be rendered.  This gives a lot of flexibility as you can reuse views while having them handled by different controllers if desired.  This is essentially an implementation of Martin Fowler's Front Controller design pattern from his seminal Patterns of Enterprise Application Architecture book. 

	<control>
		<!-- /content/hello URL handler -->	
		<command name="content.hello">
			<viewheader>content.header</viewheader>
			<viewfooter>content.footer</viewfooter>
			<view>content.hello</view>
		</command>
	</control>

The controller: hello.class.php
-------------------------------
Here is the controller for /content/hello.  We always extend the default command, which handles some basic initialization for us.  We grab the $page template instance and set a Smarty variable to the user's first name.

	<?php

	class PFHelloCommand extends PFDefaultCommand { 

		public function doExecute(PFRequest $request) {	
			parent::doExecute($request);

			$user = PFFactory::getInstance()->createObject('content.userhelper');
			$page = PFRegistry::getInstance()->getPage();
			$page->assign('FIRSTNAME', $user->getUser(1)->getFirstName());
		}
	}
	?>
	
The view: hello.tpl
-------------------
This is a Smarty template file that Protean will auto-render.  The PF_HEADER and PF_FOOTER vars will automatically get replaced by whatever header/footer is defined within the command.xml file above.  If a site needs different header/footer files for sub-sections of the site, easy-peasy, just change the command.xml.  No mucking around with template files.

	{$PF_HEADER}

		<div id="bd"> 
			<h1>
				Hello {$FIRSTNAME}!
			</h1>
		</div>
			
	{$PF_FOOTER}
	
The model: userhelper.class.php
--------------------------------
Protean allows you a lot of flexibility in how closely you wish to couple your controller and your model.  You could call raw SQL directly from your controller class above if you wish.  However, a best practice recommendation is to create a simple domain object that your controller class can call to get its data.

And while Protean supports any level of helper/domain-object classes that a team wishes to use, we'll stick to a simple example.  Here, we have a domain object class which abstracts the ORM calls for us.  This makes it easy to migrate  model storage from one database type to another, or a mix-and-match of both.  You'll also notice in this example Protean uses Propel's ORM for clean, fast database calls.  Protean leverages existing PHP libraries as much as possible.  Other libraries are extremely simple to add as well, so you're not locked into any back-end library you don't like.  Want to install Doctrine ORM instead?  Totally doable.  Just call your Doctrine code from the domain object.

	<?php

	class PFUserHelper { 
		public static function getUser($userId) {
			return UserQuery::create()
				->filterByUserId($userId)
				->findOne();
		}
	}
	?>


FUTURE DIRECTION:
================
There are so many great frameworks and platforms available five years later, it's difficult to assume Protean is better than the other options.  One very compelling framework--if somewhat immature--is Lithium, which takes the best of modern MVC frameworks such as dependency injection, IoC, and others, and implements them by leveraging the lastest features of PHP 5.3.  Of course, the Ruby on Rails community is very strong and has some excellent ideas about minimizing the amount of code necessary to deploy a production project.

Having done so much architecture in my past, I always keep a back-burner going in my mind regarding performance.  PHP5 is probably one of the most tried-and-true platforms for scaling web applications of all that are available.  Facebook runs PHP5, both interpreted and compiled, as well as very large amounts of optimizations throughout.  Facebook itself is a testament to how well a PHP5 architecture not only performs, but also how well it integrates very cleanly with other systems when it's time to scale out sub-components such as databases or caching/middleware.

I don't know the total number of sites that Protean powers, but I am aware of a dozen or so that I've been involved with, including some at over 1,000,000 registered users.  Updates will likely include migrating to the Smarty 3 templating engine, introducing namespacing to the modules, as well as writing new endpoint adapters for more recent NoSQL engines such as Redis, Mongo, and Couch.


REQUIREMENTS:
=============
Here are the requirements for building and testing Protean:

Log
-------------------

	pear install Log

Phing 2.4.2
-------------------

	pear channel-discover pear.phing.info
	sudo pear install -a phing/phing-2.4.2

Propel 1.6.2
-------------------

	pear channel-discover pear.propelorm.org
	sudo pear install -a propel/propel_generator-1.6.2
	sudo pear install -a propel/propel_runtime-1.6.2

PHPUnit 3.4.15
-------------------

	pear channel-discover pear.phpunit.de
	pear channel-discover components.ez.no
	pear channel-discover pear.symfony-project.com
	sudo pear install -a phpunit/PHPUnit-3.4.15
	
POST-INSTALL:
=============
Some basic final post-install things to do to get your "hello world" page showing:

- make a new MySQL database for this Protean install.  Here's how I do it
	- mysqladmin -u root -p create protean
	- mysql -u root -p
	- mysql> grant all on protean.* to protean@localhost identified by 'protean';
- cd into the build/ directory, copy build-dist.properties to build.properties
- Edit this file, changing the various paths and datbase settings to suit your install
- While still in the build/ directory, run the following phing commands
	- phing config
 	- phing propel-insert
- Now in the build/ directory, you should find a file called "protean.conf". This is the Apache virtual host snippet you should add to your Apache config to get it up and running.
- Restart Apache, and visit your virtualhost in your browser, you should see that Protean is up and running!
	