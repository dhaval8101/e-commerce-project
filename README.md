# Ecommerce_api_proect Readme file
------------------------------------
Features
User authentication (signup, login, forgot password, change password, logout)
Category Module (create, read, update, delete)
Subcategory Module (create, read, update, delete)
Product Module (create, read, update, delete)
Cart Module (add, remove, view,update)
Order Module(add,update,delete,view)

# Create User module 
---------------------
* perform user signup and send link in mailtrap
* perform user login functionality token ganrate and match email and password in signup user
* perform user change-passwork link send mailtrap and ganrate token
* perform user forgot password
* peroorm migration and model
* perform searching and pagination use traits method
* user migration file add field 
* perform model and controller 
* perform validation 
* perform error message use helper method and exception handler method
* perofrm user crud functionality 
* perform user module api route
* perform user change-password and logout 

# Create Categoty Module
--------------------------
* perform category  migration table 
* perform model and controller
* perform crud functionality data insert,update,delete, and display data
* perform validation 
* perform error message use helper method and exception handler method
* perform middleware api route
* perform searching and pagination use traits method

# Create Subcategory Module
--------------------------
* perform migration table 
* perform model and controller 
* perform relationship 
* perform error message use helper method and exception handler method
* perform required validation and category_id invalid validation
* perform crud functionaity 
* perform middleware api route
* perform searching and pagination id base and name base use traits method 

# Create Product Module 
-------------------------
* perform migration table 
* perform model and controller 
* perform relationship
* perform error message use helper method and exception handler method
* perform required validation and category_id and subcategory_id invalid validation
* perform crud functionality data insert,update,delete, and display data
* perform middleware api route
* perform searching and pagination id  base use traits method 

# Create Cart Module
--------------------------
* perform migration table
* perform model and controller 
* perform validation 
* perform middleware api route
* perform relationship 
* perform error message use helper method and exception handler method
* perform crud functionality data insert,update,delete, and display data
* perform searching and pagination user_id base use traits method 

# Create Order Module
--------------------------
* perform migration table
* perform model and controller 
* perform validation 
* perform middleware api route
* perform relationship 
* perform error message use helper method and exception handler method
* perform crud functionality 
* perform searching and pagination user_id base use traits method 

# Installation
* To run the e-commerce project, follow these steps
1) Clone the repository to your local environment.
2) Install the necessary dependencies using Composer.
3) Configure the database settings in the .env file.
4) Run the database migrations to create the necessary tables.
5) Seed the database with sample data.
6) Start the development server and access the project in your postman api call.
