# ToothCare: Dental Clinic System

## Local Development Setup (XAMPP Virtual Host)

This guide will help you configure your local environment so your project runs using a clean URL like:

`http://toothcare.test`

---

# Project Structure

Make sure your project is inside XAMPP `htdocs`.

Important: Only the `public` folder should be accessible from the browser.

---

# Step 1: Enable Virtual Hosts

Open this file:

```
C:\xampp\apache\conf\extra\httpd-vhosts.conf
```

---

# Step 2: Add Virtual Host

Add this configuration at the bottom:

```apache
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot "C:/xampp/htdocs"

    <Directory "C:/xampp/htdocs">
        Options Indexes FollowSymLinks Includes ExecCGI
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs/toothcare/public"
    ServerName toothcare.test

    <Directory "C:/xampp/htdocs/toothcare/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

---

# Step 3: Update Hosts File

Open Notepad as Administrator and edit:

```
C:\Windows\System32\drivers\etc\hosts
```

Add this line:
```
127.0.0.1 toothcare.test
```

---

# Step 4: Restart Apache

Go to XAMPP Control Panel:

Stop Apache

Start Apache again

---

# Step 5: Access Your Project

Open your browser:

```
http://toothcare.test
```