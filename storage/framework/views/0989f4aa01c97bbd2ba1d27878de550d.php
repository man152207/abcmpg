<!DOCTYPE html>
<html>
<head>
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            color: #333;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            box-sizing: border-box;
        }

        label {
            display: block;
            font-size: 14px;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4facfe;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #00c2fe;
        }

        .notice {
            text-align: center;
            color: red;
            margin-top: 15px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 15px;
                max-width: 90%;
            }

            button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <form id="permissionForm">
            <label>Permission Code:</label>
            <input type="text" id="permissionCode" name="permissionCode" value="152207" required>
            <button type="button" id="validatePermission">Submit</button>
        </form>

        <form id="loginForm" action="<?php echo e(route('portal.login.post')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Password:</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>

        <div id="notice" class="notice" style="display: none;"></div>
    </div>

    <script>
        document.getElementById("validatePermission").addEventListener("click", function() {
            const permissionCode = "152207";
            const userCode = document.getElementById("permissionCode").value;
            const notice = document.getElementById("notice");

            if (userCode === permissionCode) {
                document.getElementById("permissionForm").style.display = "none";
                document.getElementById("loginForm").style.display = "block";
                notice.style.display = "none";
            } else {
                notice.style.display = "block";
                notice.textContent = "Invalid permission code. Please contact the administrator.";
            }
        });
    </script>
</body>
</html>
<?php /**PATH /home/mpgcomnp/app.mpg.com.np/resources/views/auth/customer-login.blade.php ENDPATH**/ ?>