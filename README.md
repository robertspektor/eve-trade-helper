<h1 align="center">EVE TRADE HELPER</h1>
<center>A Tool to analyse and track eve online trading opportunity</center>

## Initial Setup

### Start Docker

```
make docker-up
```

Build and startup docker container

### Run Migrations

```
make docker-migrate
```

Create Database Structure

### Run Seeder

```
make docker-seed
```

**Check installation**

Go to (http://localhost:8001)
You should see a timestamp and the status of installation.

The Tool is needed some general data to work properly. These Date have to seeded into the database from [STATIC DATA EXPORT (SDE)](https://developers.eveonline.com/resource).

### Import Public Structures 

```
make docker-import-structure
```

Importing all public structures could take a while. 
For this reason, So I decided to make this task asynchronous.

For that you have to run Laravel Queue as follows:

```
make docker-queue-work-structure
```

You can run multiply worker to get the job quicker done.

### Import Market Data

As soon all database was seeded and all public structures was imported, 
you can sync the market data.



WIP


## License

This application is open-sourced software licensed under the MIT license.



