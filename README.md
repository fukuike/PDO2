PDO2
====

Capable extended classes from **`PDO` / `PDOStatement`**.

@Version: 1.0.0  
@Author : CertaiN  
@License: BSD 2-Clause  
@GitHub : http://github.com/Certainist/PDO2  

Features
========

- You can quickly migrate from `PDO` to `PDO2` without any troubles, 
because extended class `PDO2` supports all features of native class `PDO`.
- Very flexible **Method Chaining** statements.

Overloaded Methods and New Methods
==================================

PDO2::__construct()
------------------

Same as **PDO::__construct()**, but some options are automatically set.

|Options | Values | Notes |
|:-------|:-------|:-------|
|PDO::ATTR_ERRMODE | PDO::ERRMODE_EXCETPION | PDO2 can throw **PDOException**. |
|PDO::ATTR_DEFAULT_FETCH_MODE | PDO::FETCH_ASSOC | |
|PDO::ATTR_EMULATE_PREPARES   | `FALSE`          | Disable bug-ridden fu\*king emulation. |
|PDO::ATTR_STATEMENT_CLASS    | `array('PDOStatement2', array())` | PDO2 can generate **PDOStatement2** instances for prepared statements. |

PDOStatement2::execute()<br />PDOStatement2::setFetchMode()
-----------------------

Same as **PDOStatement::execute()** or **PDOStatement::setFetchMode()**,
but these returns `$this` for method chaining.

PDOStatement2::bind()
---------------------

A wrapper method for **PDOStatement::bindValue()**.

### Arguments

- *(mixed)* *__$name__*  
  The placeholder **index** or **name**.  
  Be carefull for differences of start indices.  
  
| Methods | Start Indices |
|:--|:-------:|
|PDOStatement::bindValue() | 1 |
|PDOStatement::bindParam() | 1 |
|PDOStatement::execute() | 0 |
|**PDOStatement2::bind()** | **0** |
|**PDOStatement2::bindAll()** | **0** |
|**PDOStatement2::execute()** | 0 |
  
- *(mixed)* *__$value__*
  Bound value.
  
- *(mixed)* *__\[$type\]__*  
  Choose appropriate type constant or alias char. Default value is **PDO::PARAM_STR**.

| Type    | Constants      | Alias chars | Notes                                          |
|:-------:|:---------------|:-----------:|:-----------------------------------------------|
| BOOL    |PDO::PARAM_BOOL | b           |                                                |
| NULL    |PDO::PARAM_NULL | n           |                                                |
| INT     |PDO::PARAM_INT  | i           | Also can be used for **FLOAT** and **DOUBLE**. |
| TEXT    |PDO::PARAM_STR  | s           |                                                |
| BLOB    |PDO::PARAM_LOB  | l           | For stream resources.                          |
| TEXT    |**PDO2::PARAM_LIKE** | **L**  | Propery formatted for **LIKE** search.         |

### Return Value

Return `$this` for method chaining.

### Examples

```php
$sql = 'SELECT * FROM people WHERE country = ? AND age = ? AND address LIKE ?';
$people = $pdo->prepare($sql);
              ->bind(0, 'JAPAN')
              ->bind(1, 21, PDO::PARAM_INT)
              ->bind(2, 'Nagoya', PDO2::PARAM_LIKE)
              ->execute()
              ->fetchAll();
```

Colons can be omitted.

```php
$sql = 'SELECT * FROM people WHERE country = :country AND age = :age AND address LIKE :address';
$people = $pdo->prepare($sql);
              ->bind('country', 'JAPAN')
              ->bind('age', 21, 'i')
              ->bind('address', 'Nagoya', 'L')
              ->execute()
              ->fetchAll();
```

PDOStatement2::bindAll()
------------------------

### Arguments

- *(array)* *__$values__*  
  Same as argument for **PDOStatement::execute()**.
- *(mixed)* *__\[$format\]__*  
  See the follwing notes. Default value is **PDO::PARAM_STR**.
  
### Return Value

Return `$this` for method chaining.
  
#### Format 1:  Query String

- **Comma-Separeted** alias chars.
- Numeric indices can be omitted.
- `'s'` can be ommited because default type is **PDO::PARAM_STR**.

```php
$sql = 'SELECT * FROM people WHERE country = ? AND age = ? AND address LIKE ?';
$people = $pdo->prepare($sql);
              ->bindAll(array('JAPAN', 21, 'Nagoya'), 's,i,L')
              ->execute()
              ->fetchAll();
```

```php
$sql = 'SELECT * FROM people WHERE country = ? AND age = ? AND address LIKE ?';
$people = $pdo->prepare($sql);
              ->bindAll(array('JAPAN', 21, 'Nagoya'), '1=i,2=L')
              ->execute()
              ->fetchAll();
```

```php
$sql = 'SELECT * FROM people WHERE country = :country AND age = :age AND address LIKE :address';
$people = $pdo->prepare($sql);
              ->bindAll(array('age' => $age, 'address' => $address), 'age=i,address=L')
              ->execute()
              ->fetchAll();
```


#### Format 2: Constant

Bind **all** parameters with specified type.

```php
$sql = 'SELECT * FROM tablets WHERE name LIKE ? AND manufacturer LIKE ?';
$people = $pdo->prepare($sql);
              ->bindAll(array('Nexus', 'Google'), PDO2::PARAM_LIKE)
              ->execute()
              ->fetchAll();
```

```php
$sql = 'SELECT * FROM tablets WHERE name LIKE :name AND manufacturer LIKE :manufacturer';
$people = $pdo->prepare($sql);
              ->bindAll(array('name' => 'Nexus', 'manufacturer' => 'Google'), PDO2::PARAM_LIKE)
              ->execute()
              ->fetchAll();
```

#### Format 3: Array

Arrays can contain both **Constants** and **Alias Chars**.

```php
$sql = 'SELECT * FROM people WHERE country = :country AND age = :age AND address LIKE :address';
$people = $pdo->prepare($sql);
              ->bindAll(
                  array('age' => $age, 'address' => $address),
                  array('age' => 'i',  'address' => 'L'     )
                )
              ->execute()
              ->fetchAll();
```

```php
$sql = 'SELECT * FROM people WHERE country = :country AND age = :age AND address LIKE :address';
$people = $pdo->prepare($sql);
              ->bindAll(
                  array('age' => $age,           'address' => $address        ),
                  array('age' => PDO::PARAM_INT, 'address' => PDO2::PARAM_LIKE)
                )
              ->execute()
              ->fetchAll();
```