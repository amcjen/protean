OVERVIEW
=========
Protean is a PHP5 framework that I began developing in 2005 while flying back from a Debian Linux conference held in Helsinki Finland.  Something about the altitude triggered me to realize there must be an easier, better way to deploy PHP5 projects quickly, safely, while leveraging as many existing top-notch packages such as Smarty, PropelORM, PHPUnit, Phing, and others.  

I took the initial base ideas of the framework from Matt Zandstra (now at Yahoo!), who wrote an excellent book on making clean, testable PHP5 code.  I had additional requirements, such as one-line testability, support for continuous integration, multi-lingual support, and easy templating for front-end developers.

HOW IT WORKS:
============
It's quite simple at it's core--Protean is an MVC-based, inversion-of-control framework.  There are several modules. Each module can live alone or with others. Each is self-contained, meaning it has all information within it to handle all routing of controllers, view template rendering, and model support--which includes both ORM and domain-object mapping.

*Note* - The simplicity of the examples here hopefully do not imply a simplicity or lack of knowledge of PHP5.  There is some pretty cool black magic going on in the internals of Protean to automatically show the proper templates, call the proper controller class, auto-load the domain/ORM object classes, as well as instantiate the page flow.  PHP5's Reflection classes and variable variables are used to great extent to allow the code a development team has to write day in and day out to be as simple as shown in the examples here.

How Protean routes: command.xml
-------------------------------
	<!-- simple route that exposes the /content/hello URI.  The <command> name attribute defines
	what controller is called.  The view tags describe what header/footer and body views will be 
	rendered.  This gives a lot of flexibility as you can reuse views while having them handled by 
	different controllers if desired.  This is essentially an implementation of Martin Fowler's 
	Front Controller design pattern from his Patterns of Enterprise Application Architecture book. 
	-->

	<control>
		<!-- /content/hello controller/view -->	
		<command name="content.hello">
			<viewheader>content.header</viewheader>
			<viewfooter>content.footer</viewfooter>
			<view>content.hello</view>
		</command>
	</control>

FUTURE DIRECTION:
================
There are so many great frameworks and platforms available five years later, it's difficult to assume Protean is better than the other options.  One very compelling framework--if somewhat immature--is Lithium, which takes the best of modern MVC frameworks such as dependency injection, IoC, and others, and implements them by leveraging the lastest features of PHP 5.3.  Of course, the Ruby on Rails community is very strong and has some excellent ideas about minimizing the amount of code necessary to deploy a production project.

Having done so much architecture in my past, I always keep a back-burner going in my mind regarding performance.  PHP5 is probably one of the most tried-and-true platforms for scaling web applications of all that are available.  Facebook runs PHP5, both interpreted and compiled, as well as very large amounts of optimizations throughout.  Facebook itself is a testament to how well a PHP5 architecture not only performs, but also how well it integrates very cleanly with other systems when it's time to scale out sub-components such as databases or caching/middleware.

I don't know the total number of sites that Protean powers, but I am aware of a dozen or so that I've been involved with, including myshape.com @ over 1,000,000 registered users.  Updates will likely include migrating to the Smarty 3 templating engine, introducing namespacing to the modules, as well as writing new endpoint adapters for more recent NoSQL engines such as Cassandra, Mongo, and Couch.