# QueryBuilder

##Usage

Dependent on [Connection](https://github.com/sashagar86/Connection) 

### Methods
#### CRUD
`create($table, $data)` - insert row in table `$table` from array `$data` where `key=column`, `value=value`. Return id of inserting row

`update($table, $data, $id)` - update row with `id = $id` in table `$table`. Updating data is array $data with `$column` => `$value`

`delete($table, $id)` - delete row from table `$table` with `id` = `$id`

`getOne($table, $value, $column)` - get one row from table `$table` where `column` = `$column` with value `$value`. By default `$column` = `'id'`

#### Others
`getAll($table, $where = self::EMPTY_CONDIION, $start = 0, $limit = null)` - get rows from table with conditions `$where` and limit

`countRows()` - count rows in `getAll()`. Need to implement pagination