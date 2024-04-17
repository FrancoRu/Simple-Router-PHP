# Simple-Router-PHP

This router offers a user-friendly and straightforward application, presenting itself as a lightweight and efficient option. Its inception stemmed from the need for a router within a highly constrained environment, where the decision to conserve resources prompted its development over other more resource-demanding alternatives. Unlike other options, which often come bundled with additional tools and features, many of which may not be necessary for the specific project at hand, this router focuses solely on providing essential routing functionality.

## Usage

1. **Installation**: Clone the repository:
    
   ```bash
   git clone https://github.com/FrancoRu/Simple-Router-PHP.git .
   ``` 
   
2. **Ensure that files:** Ensure that config.php and router.php are in the root of your project.
 
3. **Create a .htaccess file:** Create a .htaccess file in the root directory.
    
4. **Add rules to .htaccess: In the .htaccess file, write the following rules:**

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteRule ^(.*)$ index.php [QSA,L]
```

5. **Namespaces:** Define the namespaces in router.php and in your root file. Make sure to have the namespace correctly structured to match your project's structure.

6. **Route Creation:** In your root file, declare an instance of the router:

```php
$router = new Router();
```

Then you can declare routes as follows:

```php
$router->endpoint('{Verb}', '{Path}', {...Callbacks});
```

### Notes:
- **Verb**: Can take values of: 'GET', 'POST', 'PUT', or 'DELETE'.
- **Path**: Is the route after the domain, for example, '/' is the project's root.
- **Callbacks**: Are the anonymous functions to be used when the route is matched.

Once all your routes are defined, use `start` at the end of your file declaring the routes to start the router:

```php
$router->start();
```

**Middlewares:**
To declare a middleware, simply add the middleware callback before the final function call stack.

Example:

If we have the following middleware that validates sessions in PHP:

```php
$authMiddleware = function () {
    session_start();
    $exist = isset($_SESSION['email']) && isset($_SESSION['username']) && isset($_SESSION['id']) && isset($_SESSION['role']) && isset($_SESSION['message']);

    if (!$exist) {
        header("Location: login");
        exit;
    }

    $expirationTime = $_SESSION['expiration_time'];

    if (!$expirationTime > time()) {
        header("Location: login");
        exit;
    }
};
```

We can use it in the function call stacks as follows:

```php
$router->endpoint('GET', '/home', $authMiddleware, function () {
    echo '<h1>Tiene sesion encontrada</h1>'
});
```

Note that if the session does not exist, the user is redirected to login, where in the login route the view is declared, something like this:

```php
$router->endpoint('GET', '/login', function () {
   //Aca se declara la vista de /login
});
```
