# SymfonyBog
A simple blog made with symfony, lightly inspired my Medium.

Work is still in progress.


#


Installation
* Clone the project
* Navigate to project `cd SyBlog`
* Run `composer install`
* Edit database connection string in the `.env` file
* Run `php bin/console doctrine:database:create`
* Run `php bin/console doctrine:migrations:migrate`
* Run `php bin/console doctrine:fixtures:load --append`
* Use `admin` for the username and password


#


## Blog Page (Home)
![Imgur](https://i.imgur.com/YRm3EuK.jpg)



## Single Post
![Imgur](https://i.imgur.com/cxFpAaP.jpg)



## Posts List - Admin Panel
![Imgur](https://i.imgur.com/nEFPnBL.png)
