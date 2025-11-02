<!-- customer_login_view.php -->

<div class="login-container">
    <div class="login-header">
        <h2>Customer Login</h2>
    </div>
    <form action="customer_login.php" method="POST" class="login-form">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        
        <button type="submit" class="login-button">Login</button>
    </form>
</div>
