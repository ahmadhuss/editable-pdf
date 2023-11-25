# Editable PDF

The following source code needs the **PHP 8.0** version installed.

**Install Dependencies:**

composer install  
**Regenerate the autoloader classes:**

composer dump-autoload

**API Endpoints:**

    GET -  http://localhost/v1/visa.php
    GET -  http://localhost/v1/visa-all.php
    POST - http://localhost/v1/printouts.php

**Post Body:**

    {
      "template_name": "",
      "group_id": "",
      "document_id": ""
    }
