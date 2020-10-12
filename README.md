# Vending machine challenge

This project try to reflect any manual action against vending machine with
isolated http requests.

If you try to buy a product without doing some previous actions like insert money or select a product, 
the machine will give you a convenient error. Also, it checks things about stock
or change availability.

Any state transaction will be persisted, so we have the ability to go get the current 
status anytime, and we could go to any previous state if we could want it.

## Stack
* Symfony 5
* MongoDB

## Installation

##### Set env vars
Set your local env vars in .env.dev or .env.local file or use the default .env file is up to you.

##### Docker compose up
``` bash
> docker-compose up --build
```

### Actions available
#### Check current inventory and change availability
The service officer can open our vending machine and check the inventory
```
curl -XGET -H "Content-type: application/json" http://localhost:8080/service
```

#### Populate the inventory 
The service officer can add new products and, some coins to have enough change available. 
```
curl -XPOST -H "Content-type: application/json" http://localhost:8080/service \
 -d \
"
{
    \"products\": [
        {\"name\": \"Water\", \"price\": 0.65, \"quantity\": 10, \"selector\": 1},
        {\"name\": \"Soda\", \"price\": 1.50, \"quantity\": 1, \"selector\": 2},
        {\"name\": \"Juice\", \"price\": 1.00, \"quantity\": 1, \"selector\": 3}
    ],
    \"change\" : [
        {\"0.05\": \"10\"},
        {\"0.10\": \"10\"},
        {\"0.25\": \"10\"},
        {\"1.00\": \"10\"}
    ]
}"

```
#### Select a product
As users, we want to select a product. If we try to select a non existing product we get
a wonderful 400 error back.
```
curl -XPOST -H "Content-type: application/json" http://localhost:8080/select-product \
 -d "{\"selector\": 1}"
```

#### Insert coins
As users, we should insert some coins to buy something. Our machine only accepts some kind
of coins, so if you try to insert a non allowed coin, you will get another wonderful
400 error back.
```
curl -XPOST -H "Content-type: application/json" http://localhost:8080/insert-coin \
 -d "{\"coin\": 0.25}"
```

#### Return our money
As users, we can get our money back any time. Press the return coin button and all your
money will back to you as soon as possible!
```
curl -XPOST -H "Content-type: application/json" http://localhost:8080/return-money
```

#### Buy a product
As users, we can buy some products! If any previous required action has not been 
applied, don't worry you'll have notice as soon you press the button. After a 
successful response you could check the inventory again. It should be updated!
```
curl -XPOST -H "Content-type: application/json" http://localhost:8080/buy
```

#### RUNNING TESTS
```
docker-compose exec php bin/phpunit 
```
