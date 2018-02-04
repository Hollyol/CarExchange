# Entities

This file show details about each entity used in the app.
Some constraints are provided bellow the corresponding entities. These
constraints are the ones specified in the src/Entity/"entity" file.  
**Be aware** that some constraints may be specified in some form. See the
[Form Guide](FormGuide.md) for more details.

## Advert

The advert is the most important entity of the app. It contains all the
information needed for a exchange.

### Attributes

A list of attributes. Some attributes may be others entities and are not
listed bellow, to see those, go to the Entities section of **this** file.

**beginDate** (*Datetime*) :
The date when the advert is valid. It can be rented from that date + 1 day.  
*Constraints* :
- NotBlank

**endDate** (*Datetime*) :
The date when the advert is not available any more. It can be rented until that
date - 1 day.  
*Constraints* :
- NotBlank
- GreaterThanBeginDate (Callback)

**title** (*string*) :  
The title of the advert. If not provided by user, it will be auto-generated.  
*Contraints* :
- Max Length: 100

### Entities

A list of all entities engaged in a relationship with **Advert**.

**car** (*Car*) :  
The car linked to the advert. It is persisted and removed in the same time as
the advert. The car is *totaly dependend* on the advert and can not exist
without it. An advert *can not exists* without a car either.  
*Relationship* : Unidirectionnal one to one.

**location** (*Location*) :  
The place the car is located. By default, it is the location of the owner, but
it can be modified at the creation of the advert. The location is *totaly
independent* on the advert, but the advert *can not exist* without a location
It **may** be persited at the creation of the advert, but will never be
removed at the deletion.  
*Relationship* : Unidirectionnal many to one.  
*Constraints* :
- Valid
- NotBlank

**billing** (*Billing*) :  
The way the owner of the advert wants to get payed. The billing is *totaly
dependent* on the advert and cannot exists without it. In the same way, an
advert *can not exist* without a billing.It is persisted and removed in the
same time as the advert.  
*Relationship* : Uniderectional one to one.  
*Constraints* :
- Valid

**owner** (*Member*) :  
The member owning the advert. The owner is *totaly independant* on the advert.
However, the advert *can not exist without an owner*. The owner **must** be
persisted before the advert is created (a non logged user can't create an
advert anyway), and will not be removed at the deletion of the advert.  
*Relationship* : Bidirectional Many to one, Owner.  
*Constraints* :
- Valid

**rentals** (*Collection* of *Rental*) :  
The rentals linked to the advert. A rental is *totaly dependant* on the
advert. The rentals are persited and removed independantely from the advert.  
*Relationship* : Bidirectional One to many, Inverse.

### Methods

The setters and getters will not be listed here (or anywhere). But any
attribute as one (exept id because it should be ignored).

**hydrate** (args : *array*; return *null*)  
Fills the advert object with the values of the array given as argument. This
array **must be formated as** : [attribute-name => attribute-value]. Any key
that doesn't correspond to an attribute will be ignored, **without triggering
error**.

**isRented** (args: *Datetime*, *Datetime*; return *bool*)  
Checks if *this* advert is rented (e.g : has a valid rental) during the period
provided as argument. The first date is the beginning and the second one the
end. **Those dates can not be reversed**.

**isValid** (args: *Datetime*, *Datetime*; return *bool*)  
Checks if *this* advert is available (e.g : provided period is included by the
validity period, see *beginDate* and *endDate*.) for during the period provided
as argument. **Those dates can not be reversed**.

**setDefaultTitle** (args: *null*; return: *null*)  
**Lifecycle Callback** : This method is called on persit event. It sets a
default title to the advert *if and only if* the owner didn't provided one.
This default title includes the brand of the car and a global location.

**__construct** (args: *array*; return: *null*)  
Sets the *beginDate* and *endDate* to *now* and then, *hydrate* the advert with
the array provided as argument.

**addRental** (args: *Rental*; return: *Advert*)  
Add the rental to the collection and set the advert of the rental to *this*.

### Repository Methods

The advert entity has a few custom methods in its repository.

**hasValidPeriod** (args: *QueryBuilder*, *Datetime*, *Datetime*; return *null*)  
Checks if the advert is available and not rented during the provided period.
If the *beginDate* or *endDate* is not provided, an execption is thrown.

**whereCarLike** (args: *QueryBuilder*, *Car*)  
Checks that the car linked to *this* advert has the same number of sits and
fuel as the car provided as argument. If the car provided as argument doesn't
have any sits or fuel, those caracteristics are omitted.

**whereTownIs** (args: *QueryBuilder*, *string*)  
Checks that the location linked to *this* advert has the same town as the one
provided as argument. **Beware** : This methods checks the town **only**, not
the state or country. If no town is provided, this method doesn't do anything.

**whereStateIs** (args: *QueryBuilder*, *string*)  
Checks that the location linked to *this* advert has the same state as the one
provided as argument. **Beware** : This methods checks the stae **only**, not
the country. If no state is provided, this method doesn't do anything.

**whereCountryIs** (args: *QueryBuilder*, *string*)  
Checks that the location linked to *this* advert has the same country as the one
provided as argument. If no country is provided, an exception is thrown.

**fetchSearchResults** (args: *Advert*; return *Collection* of *Advert*)  
Fetch all adverts that match the one provided as argument. It uses :
- *hasValidPeriod*
- *whereCarLike*
- *whereTownIs*
- *whereStateIs*
- *whereCountryIs*

## Billing

The billing describes the way an owner would like to get payed.

### Attributes

**currency** (*string*)  
The currency used for the transaction.
*Constraints* :
- NotBlank
- Currency

**timeBase** (*string*)  
The timeBase of a transaction. Typicaly, this should be one of the following:
- hour
- day
- mounth
- year

*Constraints* :
- NotBlank
- Max Length: 15

**price** (*integer*)  
The price the renter has to pay in "currency" for one unit of "timeBase".  
*Constraints* :
- NotBlank

### Entities

**advert** (*Advert*)  
A billing is tied to an advert. It cannot exist without an advert. The billing
is persisted and removed in the same time as the advert.  
*Relationship* : Not aware (*Not the owner on unidirectional relationship*)

### Methods

This entity has the usual getters and setters for each attribute, but they
won't be detailed here (or anywhere).

## Car

The car describes the car you want to rent or get rented.

### Attributes

**brand** (*string*)  
The brand of the car.  
*Constraints* :
- Max Length: 100

**model** (*string*)  
The model of the car.  
*Constraints* :
- Max Length: 50

**description** (*string*)  
A short description of the car. Its owner may add commentaries about it
here.  
*Constraints* :
- Max Length: 500

**sits** (*integer*)  
The number of sits the car has.  
*Constraints* :
- GreaterThan: 0

**fuel** (*string*)  
The fuel used by the car  
*Constraints* :
- Max Length: 50

### Entities

**advert** (*Advert*)  
A car is tied to an advert. It cannot exist without it. The car is persisted
and removed in the same time as the advert  
*Relationship* : Not aware (Not the owner of unidirectional relationship)  

### Methods

There are the usual getters and setters for each attribute, but they won't be
detailed here.

**hydrate** (args: *array*; return *null*)  
Fills *this* car with the values of the array provided as argument. The array
**must be formated as** : [attribute-name => attribute-value]. If the array
contains a key that is not an attribute, it will be ignored.

**__construct** (args: *array*; return *null*)  
*hydrate* the car with the array given as argument.

## Location

The location represents a place. It is independant on *adverts* or *members*
but may be persited in the same time.

### Attributes

**country** (*string*)  
Represents the country (ISO 3166 alpha-2 code).  
*Constraints* :
- Country
- NotBlank

**state** (*string*)  
Represents the state.  
*Constraints* :
- Max Length: 50

**town** (*string*)  
Represents the town.  
*Constraints* :
- Max Length: 100

### Methods

There are the usual setters and getters, but they will not be detailed here (or
anywhere).

## Member

A member is a user of the app owning an account on it. It implements
UserInterface and Serializable.

### Attributes

**language** (*string*)  
The language used by user (one of the supported languages).  
*Constraints* :
- Language
- NotBlank

**username** (*string*)  
The pseudo user by the member  
*Constraints* :
- Max Length: 50
- NotBlank

**password** (*string*)  
The password of the user. This password is encoded at the creation of the
member (see the [Form Guide](FormGuide.md) for details).  
*Constraints* :
- NotBlank

**phone** (*string*)  
The phone number of the user.  
*Constraints* :
- Max Length: 50

**mail** (*string*)  
The e-mail address of the user.  
*Constraints* :
- Max Langth: 100
- NotBlank
- Email

**roles** (*array*)  
The roles the user has (equivalent to permissions)  

### Entities

**adverts** (*Collection of Advert*)  
The adverts the member owns. These adverts are removed in the same time as the
member.  
*Relationship* : Bidirectional one to many, Inverse

**rentals** (*Collection of Rental*)  
The rentals the member contracted. Those rentals are removed in the same time
as the member.  
*Relationship* : Bidirectional one to many, Inverse

**location** (*Location*)  
The location of the member. It is also the default value of her/his adverts.
The location *may* be created in the same time as the member, however, it will
never been removed with him/her.  
*Relationship* : Unidirectional one to one

### Methods

There are the usual getters and setters, but they won't be detailed here (or
anywhere).

**setDefaultRoles** (args: *null*; return *null*)  
**Lifecycle Callback**: this method is called on persist.  
Sets the role for a new member (ROLE\_USER).

**serialize** (args: *null*; return *string*)  
returns a string containing id, username and password. Used for authentication.

**unserialize** (args: *null*; return *array*)  
unserialise the serialized string.

**addAdvert** (args: *Advert*; return *Member*)  
Add the advert provided as argument to the Collection, and sets its owner to
*this* Member.

**addRental** (args: *Return*; return *Member*)  
Add the rental provided as argument to the Collection, and sets its renter to
*this* Member.

**removeAdvert** (args: *Advert*; return *null*)  
Remove the advert given as argument from the collection.

**removeRental** (args: *Rental*; return *null*)  
Remove the rental provided as argumetn from the collection.

### Repository Methods

The member entity has methods in its repository.

**loadUserByUserName** (args: *string*; return *Member|null*)  
Search a user in database. Returns the first user that as username **or** mail
like the first argument. If none is found, return null.

## Rental

The rental entity represents a rental.

### Attributes

**beginDate** (*Datetime*)  
The date when the rental begins.  
*Constraints* :
- NotBlank
- AdvertIsAvailable (CallBack), checks that the Advert is not rented
and is valid between the *beginDate* and *endDate*.

**endDate** (*Datetime*)  
The date when the rental ends  
*Constraints* :
- NotBlank
- GreaterThanBeginDate (Callback)

**status** (*string*)  
The status of the rental.  
*Constraints* :
- Max Length: 25

### Entities

**advert** (*Advert*)  
The advert beiing rented.  
*Constraints* :
	- Valid

**renter** (*Member*)  
The member that initiated the rental.  
*Constraints* :
	- Valid

### Methods

There are the usual setters and getters for each attribute, but they will not
be detailed here (or anywhere).
