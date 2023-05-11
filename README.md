### /games
#### description
Creates a new game for the user.
#### method
POST
#### form-data
NOQ - the number of questions that you want the game to have.  
api_key . the users api key.  
#### return
(json) game_id - the id of the game created.
### /question
#### description
Get the current question from a game.
#### method
GET
#### form-data
game_id - the id of the game you want to get the question from.  
api_key . the api_key for the user that is set on the game.  
#### return
(json) 
the_question - the actuall question  
alt_1 - alternative 1  
alt_2 - alternative 2  
alt_3 - alternative 3  
alt_4 - alternative 4  
### /postAnswer
#### description
Send a answer to the question that is current in a game.
#### method
POST
#### form-data
api_key . the api_key for the user that is set on the game.  
asnwer - The answer either 1, 2, 3 or 4.
game_id - the id of the game you want to post to.
#### return
(json) 
result - either true if the answer was correct or false if the answer was incorrect.  
gameDone - true if the game is done or false if not.
### /brewCoffe
#### description
Attempt to brew coffe
#### return
##### status Code
418
