# Mysql search test

Based on SO question http://stackoverflow.com/questions/40739764/search-products-by-attributes-from-database-in-optimal-way

This test compares two approaches for product search by attributes in MySQL database:

1. Search attributes by JOINS with ProductAttribute table.
2. Search attributes by generated index table, where all attributes are columns.

SPOILER: Index table is much faster.

## Instructions

Let's create 200k products and test SELECT queries.

1. Set up database connection in init.php.
2. Create tables with tables.php.
3. Populate tables with populate.php (it creates 200k products).
4. Generate query with query.php and run it. On my computer it takes about 4.5s to execute.
5. Create index table with create-index-table.php.
6. Fill index table with fill-index-table.php.
7. Generate query with query-index.php and run in. On my computer it takes 0.2s to execute.

Results:

 * Miltiple JOINs query - 4.5s.
 * Query by index table - 0.2s.
