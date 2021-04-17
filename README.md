# PHP Hackathon
This document has the purpose of summarizing the main functionalities your application managed to achieve from a technical perspective. Feel free to extend this template to meet your needs and also choose any approach you want for documenting your solution.

## Problem statement
*Congratulations, you have been chosen to handle the new client that has just signed up with us.  You are part of the software engineering team that has to build a solution for the new client’s business.
Now let’s see what this business is about: the client’s idea is to build a health center platform (the building is already there) that allows the booking of sport programmes (pilates, kangoo jumps), from here referred to simply as programmes. The main difference from her competitors is that she wants to make them accessible through other applications that already have a user base, such as maybe Facebook, Strava, Suunto or any custom application that wants to encourage their users to practice sport. This means they need to be able to integrate our client’s product into their own.
The team has decided that the best solution would be a REST API that could be integrated by those other platforms and that the application does not need a dedicated frontend (no html, css, yeeey!). After an initial discussion with the client, you know that the main responsibility of the API is to allow users to register to an existing programme and allow admins to create and delete programmes.
When creating programmes, admins need to provide a time interval (starting date and time and ending date and time), a maximum number of allowed participants (users that have registered to the programme) and a room in which the programme will take place.
Programmes need to be assigned a room within the health center. Each room can facilitate one or more programme types. The list of rooms and programme types can be fixed, with no possibility to add rooms or new types in the system. The api does not need to support CRUD operations on them.
All the programmes in the health center need to fully fit inside the daily schedule. This means that the same room cannot be used at the same time for separate programmes (a.k.a two programmes cannot use the same room at the same time). Also the same user cannot register to more than one programme in the same time interval (if kangoo jumps takes place from 10 to 12, she cannot participate in pilates from 11 to 13) even if the programmes are in different rooms. You also need to make sure that a user does not register to programmes that exceed the number of allowed maximum users.
Authentication is not an issue. It’s not required for users, as they can be registered into the system only with the (valid!) CNP. A list of admins can be hardcoded in the system and each can have a random string token that they would need to send as a request header in order for the application to know that specific request was made by an admin and the api was not abused by a bad actor. (for the purpose of this exercise, we won’t focus on security, but be aware this is a bad solution, do not try in production!)
You have estimated it takes 4 weeks to build this solution. You have 2 days. Good luck!*

## Technical documentation
### Data and Domain model
I use user, admin, room, reservation and programme models.
User contains an id and cnp.
Admin contains an id and an authentication token.
Room contains an id and name.
Reservation contains an id, user id and programme id.
Programme contains an id, name, starting  date, end date, room id and max number of participants.
### Application architecture
Each model has its own separate controller. Create and Delete methods are available.
###  Implementation
##### Functionalities
- [x] Create User
- [x] Remove User
- [x] Validate User CNP
- [x] Create Room
- [x] Remove Room
- [x] Create Programme
- [x] Remove Programme
- [x] Create Reservation
- - [x] Refuse if programme is at max capacity
- - [x] Refuse if user has another reservation in that time interval
- [x] Remove Reservation
##### Business rules
CNPs must be validated, Rooms, Reservations and Programmes must be validated to not have null reference keys.
##### 3rd party libraries (if applicable)
I use the symfony framework using friendsofsymfony/rest-bundle to create the REST methods. I also use the usual symfony libraries for database access, controller creation, entity creation and so on.

##### Environment
| Name | Choice |
| ------ | ------ |
| Operating system (OS) | Windows 10 Pro |
| Database  | MySQL 8.0|
| Web server| Apache |
| PHP | 7.0 |
| IDE | PhpStorm |

### Testing
I've used Insomnia for manual testing of the API.

## Feedback

1. Have you ever been involved in a similar experience? If so, how was this one different?
 
 I have worked for schoolwork on a smiliar hackathon, technology was C++. We had to make an airplane reservation system.
 
2. Do you think this type of selection process is suitable for you?
 
 Yes, because it shows the recruiters that I know how to solve actual business problems.
 
4. What's your opinion about the complexity of the requirements?
 
 They do not seem unreasonably complex.
 
6. What did you enjoy the most?

 Finally putting into practice my PHP Symfony skills on a business requirement, not just small projects that I do on my own.
 
8. What was the most challenging part of this anti hackathon?
 
 Getting the web server to behave the way I want it to.
 
10. Do you think the time limit was suitable for the requirements?
 
 Yes.
 
12. Did you find the resources you were sent on your email useful?
 
 Somewhat, I already knew about them beforehand.
 
14. Is there anything you would like to improve to your current implementation?
 
 Automatic Routing would make things a bit easier, but I wasn't able to get the configuration file to work.
 
16. What would you change regarding this anti hackathon?
 
 Nothing, it's a good exercise.
