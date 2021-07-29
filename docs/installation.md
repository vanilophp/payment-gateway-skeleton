# {:payment_gateway_name:} Module Installation

1. Add to your application via composer:
    ```bash
    composer require {:composer_vendor:}/{:composer_package_name:} 
    ```
2. Add the module to `config/concord.php`:
    ```php
    <?php
    return [
        'modules' => [
             //...
             {:name_space_root:}\Providers\ModuleServiceProvider::class,
             //...
        ],
    ]; 
    ```

---

**Next**: [Configuration &raquo;](configuration.md)
