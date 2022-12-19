# Library API

## Project installation
1. Clone repository
```
git clone https://github.com/Pofke/kilo-library.git
```

2. Install composer
```
composer install
```
3. Copy .env.example file and specify database settings
```
cp .env.example .env
```
4. Open Docker and build service:
```
docker-compose build
```
5. Create and start containers
```
docker-compose up -d
```
6. Migrate and seed database
```
docker exec library-php php artisan migrate --seed
```
7. Generate tokens
[http://127.0.0.1:8000/setup](http://127.0.0.1:8000/setup)


## API Commands
### Book
#### Get books
```
GET api/v1/books
```
#### Get book
```
GET api/v1/books/{book}
```
#### Store books in bulk
```
POST api/v1/books/bulk
Body:
[
	{
		"name": "ausiaus drama",
		"author": "Suniukas rexas",
		"year": 2022,
		"genre": "drama",
		"pages": 242,
		"language": "en",
		"quantity": 32
	},
	{
		"name": "ispazintis",
		"author": "Mr. Jonas Kazakevičius",
		"year": 2013,
		"genre": "biografija",
		"pages": 110,
		"language": "lt",
		"quantity": 3
	}
]


Only admin or librarian allowed
```
#### Store book
```
POST api/v1/books
Body:
{
	"name": "miauksiaus romanas",
	"author": "Katinas Leopoldas",
	"year": 2015,
	"genre": "romanas",
	"pages": 132,
	"language": "lt",
	"quantity": 12
}


Only admin or librarian allowed
```
#### Update book
```
PUT|PATCH api/v1/books/{book}
Body:
{
	"name": "miauksiaus romanas",
	"author": "Katinas Leopoldas",
	"year": 2015,
	"genre": "romanas",
	"pages": 132,
	"language": "lt",
	"quantity": 12
}
PUT - All rows needs to be included
PATCH - Only rows which will be updated needs to be included

Only admin or librarian allowed
```
#### Delete book
```
DELETE api/v1/books/{book}

Only admin or librarian allowed
```
#### Take book
```
PATCH api/v1/books/{book}/take

Only works if book is available and user don't have it already
```

### Reservation

#### Get reservations 
```
GET api/v1/reservations

Admin and librarian gets all, reader gets his
```
#### Get reservation
```
GET api/v1/reservations/{reservation}
```
#### Create reservation
```
POST api/v1/reservations
Body:
{
	"bookId": 1,
	"userId": 3,
	"status": "T"
}

Only admin or librarian allowed
```
#### Update reservation
```
PUT|PATCH api/v1/reservations/{reservation}
Body:
{
    "bookId": 1,
    "userId": 3,
    "status": "R",
    "extendedDate": "2022-10-15",
    "returnedDate": "2022-11-10"
}
PUT - All rows needs to be included
PATCH - Only rows which will be updated needs to be included

Only admin or librarian allowed
```
#### Delete reservation
```
DELETE api/v1/reservations/{reservation}

Only admin or librarian allowed
```
#### Extend reservation
```
PATCH api/v1/reservations/{reservation}/extend

Reader can extend reservation for one time
```
#### Return book
```
PATCH api/v1/reservations/{reservation}/return
```

## Filters
Filters are only available in Get Books and Get Reservations commands.
### Available operations

| Operation map | Opearations |
|---------------|:-----------:|
| eq            |      =      |
| ne            |     !=      |
| lt            |      <      |
| lte           |     <=      |
| gt            |      >      |
| gte           |     >=      |

### Books
| Parameters | Available Operations |
|------------|:--------------------:|
| name       |          =           |
| author     |          =           |
| year       |   =, <, <=, >, >=    |
| genre      |          =           |
| pages      |   =, <, <=, >, >=    |
| language   |          =           |
| quantity   |   =, <, <=, >, >=    |
Example:
```
GET api/v1/books?year[lt]=2000&pages[gte]=300&genre[eq]=horror
```

### Reservations
| Parameters   | Available Operations |
|--------------|:--------------------:|
| bookId       |          =           |
| userId       |          =           |
| status       |        =, !=         |
| extendedDate |   =, <, <=, >, >=    |
| returnedDate |   =, <, <=, >, >=    |
Example:
```
GET api/v1/reservations?status[ne]=R
```


## Author
[Povilas Baranskas](https://github.com/Pofke), 2022
