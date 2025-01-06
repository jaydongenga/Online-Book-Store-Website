<?php
$conn = mysqli_connect("localhost", "root", "", "user_registration") or die("Connection failed: " . mysqli_connect_error());
error_reporting(E_ALL);
ini_set('display_errors', 1);





?>

<!--

1. Registering Duplicate Emails: Ensure the system prevents duplicate registrations for the same email.
2. Password Mismatch: Test if the system detects when the confirmation password does not match.
3. User Login: After registration, create a login.php file to allow users to log in.





Yes, your **step 8** configuration is correct. Here's a breakdown of what your `config.php` does:

### Code Review:
```php
$conn = mysqli_connect("localhost", "root", "", "user_registration") or die("Connection failed: " . mysqli_connect_error());
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

1. **Database Connection**:
   - `mysqli_connect("localhost", "root", "", "user_registration")`: 
     - **"localhost"**: This specifies the server where your database is hosted (usually localhost for XAMPP).
     - **"root"**: The default username for XAMPP's MySQL.
     - **""** (empty string): The password field, which is empty by default in XAMPP.
     - **"user_registration"**: The name of your database.
   - `or die("Connection failed: " . mysqli_connect_error())`:
     - If the connection fails, this will output the error message so you can troubleshoot.

2. **Error Reporting**:
   - `error_reporting(E_ALL)`: Enables reporting for all PHP errors.
   - `ini_set('display_errors', 1)`: Ensures errors are displayed in the browser for debugging.

### When to Use This:
- This setup is ideal for development and debugging to identify and fix errors.
- However, **do not use `ini_set('display_errors', 1)` in production** because it exposes sensitive information, such as database connection details, to users. 
In production, use `ini_set('display_errors', 0)` instead.

---

### Suggestions for Improvement:
While your setup is correct, consider these tips:
1. **Hide Database Credentials**: If this code will be deployed, move database credentials to an `.env` file or use constants to avoid exposing them
 in the main PHP code.
 
2. **Check the Database Connection**:
   - Add a condition to ensure the `$conn` variable is set and connected:
     ```php
     if (!$conn) {
         die("Connection failed: " . mysqli_connect_error());
     } else {
         echo "Connected successfully!";
     }
     ```
     You can remove the `echo` in production.

With these checks, your setup should work perfectly. Let me know if you encounter any issues!



 -->

