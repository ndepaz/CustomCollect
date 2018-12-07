#Laravel Collection Select Statement 
[![!Issues](https://img.shields.io/github/issues/ndepaz/CustomCollect.svg?style=flat-square)](https://github.com/ndepaz/CustomCollect/issues)
[![GitHub forks](https://img.shields.io/github/forks/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/network)
[![GitHub stars](https://img.shields.io/github/stars/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/stargazers)
[![GitHub license](https://img.shields.io/github/license/ndepaz/CustomCollect.svg)](https://github.com/ndepaz/CustomCollect/blob/master/LICENSE)

##About
This package adds a select method statement to the Laravel collection class and 
allows you to use similarly as a SQL select attribute statement, and returns a collection of dummy objects.
 
#####Usage: 
Assume you have some classes something like this.
```sh
class User extends Model{
    public $email;

    public function contact(){
        return $this->hasOne(Contact::class);
    }
    public function permissions(){
        return $this->belongsToMany(Permission::class);
    }
}

class Contact extends Model{
    public $birth_date;

    public function address(){
        return $this->hasOne(Address::class);
    }

}
class Address extends Model{
    public $street_name;
}
```
##### A current of way of setting response objects.
The static method all() in this case will return a collection.
In most cases, you would need to loop through each object, after manipulating which properties will be send or hidden to front-end,
then you could create a dynamic object so it can be passed to the front end as json etc..

```sh
$users = User::all()
$usersJson = collect();
foreach($users as $user){
    $new_user = new \sdClass();
    $new_user->{"username"} =$user->email;
    $new_user->{"birth_date"} = $user->contact->birth_date; 
    $usersJson->push($new_user);
    .......
    ....
    ..
}
return $usersJson->toJson();
```
##### A much convenient way of setting response objects in collection.
To save some time in data presentation you can use the select statement with a property and its new property name separated by " as ".
Also, you can traverse through embedded relation or properties within the given class using the dot operation.
```sh
User::all()->select("email as userName","contact.birth_date","contact.first_name as name")->toJson();
```
Note that eager loading it's not necessary. The select method currently supports only getters to fetch its values.


##### You can also have a callback per looped object.
For properties that need more than just renaming, simply define a method callback at the end the arguments like so. 
The method callback will contain two parameters.
 - The $dummy object is passed per reference so no need to specify a return statement.
 - The $userObject is passed by value and can be used to concat properties. 
```sh
 User::all()->select("contact.first_name as name", function($dummy,User $userObject){
        $dummy->{"fullName"}=$userObject->contact->first_name . ' ' . $userObject->contact->last_name;
 });
```
##### Remarks
In a select method string argument, you can go several properties deep as shown below.
```sh
 User::all()->select("contact.address.street_name as Street","contact.phone as CellPhone");
```
<br/>
<br/>

That's it. Best Regards!
