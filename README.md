[![!Issues](https://img.shields.io/github/issues/ndepaz/CustomCollect.svg?style=flat-square)](https://github.com/ndepaz/CustomCollect/issues)
[![GitHub forks](https://img.shields.io/github/forks/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/network)
[![GitHub stars](https://img.shields.io/github/stars/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/stargazers)
[![GitHub license](https://img.shields.io/github/license/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/blob/master/LICENSE)

# Laravel Collection Select Statement 

## About

This package adds a select method statement to the Laravel collection class and 
allows you to use it similar to a SQL select attribute statement and returns a collection of dynamic objects. Note that 
laravel select statement before a fetch should still be used. This is approach better fits mutating instances that don't 
extend the laravel Model class.
 
## Usage: 

Lets say you have some classes like these. 

```sh
class Calendar {
    public $owner_name;
    /* @return collection */
    public function getEventsPerRange($startDate, $endDate){
        //TODO: implement method.
    }
}

class EventRepo extends Calendar {
    /* @var Collection $events */
    protected $events;
    public function __constructor(array $events){
        $this->events = collect($events);
    }
    public function selectEventsPerDateRange($startDate, $endDate){
        $this->events = $this->getEventsPerRange($startDate, $endDate);
        return $this;
    }
    public function get()
    {
        return $this->events;
    }
}

class Event {
    public $id,$owner_first_name,$owner_last_name,$address,organizer,$summary;
    //NOTE: somehow these properties get updated.

    public function getOrganizer(){
        return $this->organizer;
    }
    public function setOrganizer(Organizer $organizer){
        return $this->organizer =$organizer;
    }
}
class EventOrganizer {
    public $displayName,$email,$id;
}
```
### A current of way of setting response objects.
The static method get() in this case will return a collection.
In most cases, you would need to loop through each object, after manipulating which properties will be send or hidden to front-end,
then you could create a dynamic object so this can be passed to the front end as json etc..

```sh
$repo = new EventRepo($events);
$events = $repo->selectEventsPerDateRange($startDate, $endDate)->get();
$eventsJson = collect();
foreach($events as $event){
    $event = new \sdClass();
    $event->{"organizer_name"} = $event->organizer->displayName;
    $event->{"contact_email} = $event->organizer->email;
    $eventJson->push($event);
}
return $eventsJson->toJson();
```
### A much more convenient way of setting response objects in a collection.
To save some time in data presentation you can use the select statement with a property and its new property name separated by " as ".
Also, you can traverse through embedded relation or properties within the given class using the dot operator.Note, in the select method string argument, 
you can go several properties deep for the objects it will be transversing as shown below.
```sh
$events = $repo->selectEventsPerDateRange($startDate, $endDate)->get();
return $events->select("id as event_id"
    "organizer.displayName as organizer_name", 
    "organizer.email as contact_email")->toJson();
```
Note that eager loading it's not necessary. The select method currently supports only getters to fetch its values.


### You can also have a callback per looped object.
For properties that need more than just renaming, simply define a method callback at the end the arguments like so. 
The method callback will contain two parameters.
 - The $dummy object is passed per reference so no need to specify a return statement.
 - The $eventObject is passed by value and can be used to concat properties. 
```sh
 $events = $repo->selectEventsPerDateRange($startDate, $endDate)->get();
 return $events->select("organizer.displayName as organizer_name", "organizer.email as contact_email"
 function($dummy,Event $eventObject){
    $dummy->{"full_name"} = $eventObject->owner_first_name . ' ' . $eventObject->owner_last_name;
 })->toJson();
```
### Package Installation
Install it in your project via composer require.
```sh
composer require ndp/customcollect
```
Optional:
```sh
composer dump-autoload
```

<br/>
<br/>

##### That's it. Best Regards!

