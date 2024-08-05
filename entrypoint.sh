cd /app
php yii migrate/down all --interactive=0
php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0
php yii migrate --interactive=0
php yii fixture/load User --interactive=0
php yii fixture/load Author --interactive=0
php yii rbac/init --interactive=0
apache2-foreground