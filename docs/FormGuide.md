#Forms

This app uses many form. This file shows them and tell how they work.
each form is tied to an entity, but each entity doesn't have one only form.
However, all forms tied to the same entity extends from the same "abstract"
form (the term abstract should not be understood the way object oriented
programation suggests it. Such forms are only meant to describe a global
behaviour and **must not** be used in the application).

**Be aware** : Some forms have constraints. Those constraints are specific to
the form, and are used in **addition** to the constraints described in the
entity file (src/Entity/...). See the [Entity Guide](docs/EntityGuide.md) for
more details.

**Additional note** : When fields or types are changed, the modifications are
performed thanks to the OptionsSetter. See the [Service Guide](docs/ServiceGuide.md)
for mor informations.

##Advert Forms

This section is about forms tied to the entity *Advert*.

###AbstractAdvertType

This form is meant to describe a global behaviour and should not be used
directly in the app.

####Fields :
	- title, TextType
	- beginDate, DateType
	- endDate, DateType
	- car, AbstractCarType
	- location, AbstractLocationType
	- billing, BillingType

####Constraints :
	-futureDate (Callback), applies to :
		- beginDate
		- endDate

####Subscribers

This form has subscribers, they may call some services. See the [Service Guide](docs/ServiceGuide.md)
for more informations.

This form calls
	- AdvertFormatingService on SUBMIT.

###AddAdvertType

This form extends the AbstractAdvertType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractAdvertType
specifications above for the remaining infos.

####Fields :
	- location, AddLocationType
	- car, AddCarType

####Translation Domains

This form is translated using the "addAdvert" domain. The fields
"location" and "car" are translated using "addLocation" and "addCar",
respectively.

###SearchAdvertType

This form extends the AbstractAdvertType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractAdvertType
specifications above for the remaining infos.

####Fields :
	- title, **removed**
	- billing, **removed**
	- car, SearchCarType
	- location, SearchLocationType

####Translation Domains

This form is translated using the "searchAdvert" domain. The fields
"location" and "car" are translated using "searchLocation" and "searchCar",
respectively.

##Billing

This section contains one only form, since the billing is not used while
searcing an advert.

###BillingType

####Fields :
	- currency, CurrencyType
	- price, IntegerType
	- timeBase, ChoiceType :
		- hour
		- day
		- month
		- year

####Translation Domain

This form is translated using the "addBilling" domain.

##Car

This section contains all forms tied to the entity *Car*.

###AbstractCarType

This form is meant to describe a global behaviour and should not be used
directly in the app.

####Fields :
	- brand, TextType
	- model, TextType
	- sits, IntegerType, data = 5
	- fuel, Choicetype :
		- diesel
		- SP-98
		- SP-95
		- electric
		- hybrid
	- description, TextareaType

###AddCarType

This form extends the AbstractCarType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractCarType
specifications above for the remaining infos.

####Translation Domain

This form is translated using the "addCar" domain.

###SearchCarType

This form extends the AbstractCarType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractCarType
specifications above for the remaining infos.

####Fields :
	- brand, **removed**
	- model, **removed**
	- description, **removed**

####Translation Domain

This form is translated using the "searchCar" domain.

##Location

This section is about all forms tied to the entity *Location*

###AbstractLocationType

This form is meant to describe a global behaviour and should not be used
directly in the app.

####Fields :
	- country, CountryType, default : FR
	- state, TextType
	- town, TextType

####Subscribers

This form has subscribers, they may call some services. See the [Service Guide](docs/ServiceGuide.md)
for more informations.


This form calls :
	- LocationFormatingService on SUBMIT

###AddLocationType

This form extends the AbstractLocationType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractLocationType
specifications above for the remaining infos.


####Subscribers

This form calls LocationRepository::avoidDuplicate on SUBMIT. See the [Entity
Guide](docs/EntityGuide.md) for more informations.

####Translation Domain

This form is translated using the "addLocation" domain.

###SearchLocationType

This form extends the AbstractLocationType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractLocationType
specifications above for the remaining infos.

####Fields :
	- state, **not required**
	- town, **not required**

####Translation Domain

This form is translated using the "searchAdvert" domain

##Member

This section is about all forms tied to the entity *Member*.

###AbstractMemberType

This form is meant to describe a global behaviour and should not be used
directly in the app.

####Fields :
	- username, TextType
	- password, PasswordType
	- mail, EmailType
	- phone, TextType, **not required**
	- location, AbstractLocationType
	- language, ChoiceType (supported languages)

###MemberSignUpType

This form extends the AbstractMemberType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractMemberType
specifications above for the remaining infos.

####Fields :
	- password, RepeatedType
	- mail, RepeatedType

####Subscribers

This form has subscribers, they may call some services. See the [Service Guide](docs/ServiceGuide.md)
for more informations.

This form calls :
	- MemberFormatingService on SUBMIT
	- PasswordEncoder on POST\_SUBIT (from the security component)

##Rental

This section is about forms tied to the entity *Rental*

###AbstractRentalType

This form is meant to describe a global behaviour, and should not be used
directly in the app.

####Fields :
	- beginDate, DateType
	- endDate, DateType

####Constraints :
	- futureDate, checks that a date is in the future. Applies on :
		- beginDate
		- endDate

###AddRentalType

This form extends the AbstractRentalType. The inforations provided here are
only the ones that changes from the parent. Refer to the AbstractRentalType
specifications above for the remaining infos.

####Translation Domain

This form is translated using the "addRental" domain
