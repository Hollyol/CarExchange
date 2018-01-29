# Services

This file describes the behaviour of the services of this app.

## Forms

This section provides informations about the services applying to forms.

### OptionsSetter

The OptionsSetter is used to change the caracteristics of an **existsing**
field.

#### setOptions

##### Arguments :

**builder** (*FormBuilderInterface*)
**field** (*string*)
**options** (*array*), default: []
**type** (*string*), default: ''

##### Description :

This method can **add** options to an existing field, and / or change its
type. If the *field* provided doesn't exists, the method will return immediately.
If the *type* is not provided, it will not be changed. The *options* are added
to the existing ones or overwrites them if they already exist.

## Format

This section provides informations about the formating services. Those services
are used mostly in form subscribers.

### AdvertFormater

This service is used to format adverts. It doesn't care about formating
entities used by the advert. It is done by other services (that my be called
by their own subscriber).

##### formatTitle

This method is called by *AdvertFormater::formatAdvert()*.

###### Arguments :

**title** (*string*)

###### Description :

This method returns a capitalized version of the *title* provided as
argument.

#### formatAdvert

This method is called by a form subscriber on SUBMIT event

##### Arguments :

**advert** (*Advert*)

##### Description :

This method replace the title of the provided *advert* with a formated version
(see [formatTitle](./#formatTitle) for more details.

### LocationFormater

This service is used to format locations.

#### formatTown

This method is called by *LocationFormater::formatLocation()*

##### Arguments :

**town** (*string*)

##### Description :

This method replace any character that is not a separator (alphanumerical
or " .'-") by a space. Then it capatilize the string using the previous characters.

#### formatState

This method is called by *LocationFormater::formatLocation()*

##### Arguments :

**state** (*string*)

##### Description :

This method works exactly as [formatTown](./#formatTown)

#### formatLocation

This method is called by a from subscriber on SUBMIT event

##### Arguments :

**location** (*Location*)

##### Description :

This method replaces the *town* and *state* of the provided *location* with
the formated versions. See [formatTown](./#formatTown) and [formatState](./#formatState)
for more informations.

### MemberFormater

This service is used to format attributes of the *Member* entity.

#### formatPhone

This method is called by *MemberFormater::formatMember*

##### Arguments :

**phone** (*string*)

##### Description :

This method return a formated phone number. In the result, any non-digit
character has been replaced by a space.

#### formatMail

This method is called by *MemberFormater::formatMember*

##### Arguments :

**mail** (*string*)

##### Description :

This method converts the given *mail* to lower case and returns it.

#### formatMember

This method is called by a subscriber on SUBMIT event.

##### Arguments :

**member** (*Member*)

#### Description :

This method formats the phone number and mail address of the provided member,
see [formatPhone](./#formatPhone) and [formatMail](./#formatMail) for more details.
