#CarExchange
This is a demo project. It aims to show what i can do with the Symfony
framework.

##Entities
This project uses a few entities. Each of them are
managed by the doctrine ORM. These entities are made of several attributes and
methods. To learn more about these, see the [Entity Guide](docs/EntityGuide.md)
or check the source (src/Entity).

##Forms
Forms are really important in any project. In this project, there are two
*main* categories of forms:
	- Those used to search entities
	- Those used to add entities

All of these forms extends from an "abstract" form (it is not actualy abstract
the way object oriented programation suggests it. But those forms aren't meant
to be used in the applications) where some subscribers and constraints are
defined.
**Note** : Most constraints are defined in Entities. See the [Entity Guide](docs/EntityGuide.md).
If you want to learn more about forms, take a look at the [Form Guide](docs/FormGuide.md)
or check the source (src/Form)

##Services
A few services are available within the app. Take a look at the [Service Guide](docs/ServiceGuide.md)
for more informations. You can also see the source (src/Service).

##Repositories
There are a few customs fetching functions defined in the repositories of the
concerned entities. More details are available in the [EntityGuide](docs/EntityGuide.md)
or in the source directly (src/Repository).

##Translation
This app support two languages :
	-French
	-English

The essential of translation is done directly in templates. All URIs contains a
*locale* parameter. It is used to tell the controllers what template should be
used.

Forms are also translated thanks to xliff files (located in translations/)
