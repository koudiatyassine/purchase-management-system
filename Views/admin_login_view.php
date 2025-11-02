<div class="admin-container">
<div class="top-buttons-container" style=".top-buttons-container {position: absolute;top: 20px;right: 20px;display: flex;gap: 10px;align-items: center;}.btn-back, .btn-logout {background-color: #3498db;color: white;border: none;padding: 10px 20px;font-size: 16px;border-radius: 5px;cursor: pointer;transition: background-color 0.3s, transform 0.2s;font-family: 'Arial', sans-serif;box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);width: 150px; }.btn-back:hover {background-color: #2980b9;transform: translateY(-2px); }.btn-back:focus {outline: none;box-shadow: 0px 0px 5px 2px #2980b9;}.btn-logout:hover {background-color: #c0392b;transform: translateY(-2px); }.btn-logout:focus { outline: none; box-shadow: 0px 0px 5px 2px #c0392b;}}">
    <form action="landing.php" method="get" style="display: inline;">
        <button type="submit" class="btn btn-back">Back</button>
    </form>
</div>
<div class="login-container">
    <div class="login-header">
        <h2>Admin Login</h2>
    </div>
    <form action="admin_login_process.php" method="POST" class="login-form">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Enter your email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Enter your password" required>
        
        <button type="submit" class="login-button">Login</button>
    </form>
</div>




