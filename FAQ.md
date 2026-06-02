# Post-Deployment Questions & Answers

## Q1. Will deployment affect the GitHub repository?

Answer:

No.

During deployment we only:

```text
git clone
git checkout production-14
git pull
```

These commands download code from GitHub.

They do not:

* delete branches
* modify commits
* remove files from GitHub
* affect teammates

Only commands like:

```bash
git push
git push --force
git merge
```

can modify GitHub.

Deployment is generally safe.

---

## Q2. What branch should be deployed?

Current deployment branch:

```text
production-14
```

Do NOT use:

```text
main
```

for this project because it loads the older version.

Correct setup:

```text
Database = production-14 database
Code = production-14 branch
```

---

## Q3. How do future updates work?

Local development:

```bash
git checkout production-14
git add .
git commit -m "Update"
git push origin production-14
```

VPS:

```bash
cd ~/DMC-SYSTEM/DMC-ERP
git checkout production-14
git pull origin production-14
```

Result:

* code updates
* database remains untouched

---

## Q4. Will updating the code delete database records?

Answer:

No.

Git only manages files.

Database data is stored in MySQL.

Examples:

```text
Users
Projects
Liquidations
Suppliers
Transactions
```

remain intact after:

```bash
git pull
```

unless someone intentionally changes the database.

---

## Q5. Where is the database located now?

Current location:

```text
DigitalOcean VPS
```

Structure:

```text
VPS
├── Laravel
├── MySQL
└── dmc_erp database
```

The database is no longer on your laptop.

---

## Q6. Is the database in the cloud?

Answer:

Yes.

It is hosted inside the VPS.

However it is not publicly accessible.

Users cannot download it just by visiting the website.

---

## Q7. Is the database secure?

Current security level:

✓ SSH protected

✓ MySQL local access only

✓ Not publicly exposed

✓ No public phpMyAdmin

For an OJT/company tracking system this is reasonably secure.

Sensitive information currently includes:

* employee names
* liquidation details
* attachments
* project records

---

## Q8. How do I view database tables?

SSH into VPS:

```bash
mysql -u root
```

Then:

```sql
USE dmc_erp;
SHOW TABLES;
```

Example:

```sql
SELECT * FROM users LIMIT 10;
```

---

## Q9. How do I verify new transactions are being saved?

Example:

```sql
SELECT * FROM projects ORDER BY id DESC LIMIT 10;
```

or

```sql
SELECT * FROM liquidations ORDER BY id DESC LIMIT 10;
```

If a newly created record appears:

```text
Website
↓
Laravel
↓
MySQL
```

is working correctly.

---

## Q10. How do I create a database backup?

Create backup:

```bash
mysqldump -u root dmc_erp > /root/dmc_erp_backup.sql
```

Restore backup:

```bash
mysql -u root dmc_erp < /root/dmc_erp_backup.sql
```

---

## Q11. Can I close my laptop?

Answer:

Yes.

The website is hosted on the VPS.

Your laptop is no longer hosting the application.

---

## Q12. Will the website stay online if I close SSH?

Current answer:

Maybe.

Right now the site runs using:

```bash
php artisan serve
```

If that process stops, the site stops.

---

## Q13. Will the website survive a VPS reboot?

Current answer:

No.

After reboot:

```bash
php artisan serve
```

must be started again.

This is why Nginx + PHP-FPM is still needed.

---

## Q14. Why does the browser say "Not Secure"?

Current URL:

```text
http://165.227.83.205:8000
```

Reasons:

* using HTTP
* using raw IP address
* no SSL certificate

Browsers therefore display:

```text
Not Secure
```

---

## Q15. How do I get the secure padlock (HTTPS)?

Requirements:

```text
Domain
+
DNS pointing to VPS
+
Nginx
+
Let's Encrypt SSL
```

Example result:

```text
https://erp.company.com
```

Browser will show:

🔒 Secure

---

## Q16. Do I need to update code in VS Code or on the VPS?

VS Code:

```text
Write code
Create features
Fix bugs
Commit changes
Push changes
```

VPS:

```text
Deploy code
Pull updates
Configure server
Manage database
Install SSL
```

Code changes happen locally.

Server configuration happens on the VPS.

---

## Q17. What was the root cause of the deployment issue?

Not:

✗ PHP

✗ MySQL

✗ Laravel

✗ Database

Actual issue:

```text
Database = production-14
Code = main
```

Fix:

```bash
git checkout production-14
```

After switching branches:

✓ Updated pages appeared

✓ Accounting role worked

✓ Correct version loaded

Deployment succeeded.
