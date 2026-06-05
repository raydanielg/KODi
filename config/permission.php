<?php

return [

    'models' => [
        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */
        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */
        'roles' => 'roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */
        'permissions' => 'permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */
        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */
        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */
        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        /*
         * If you want to use a custom column name for your model_has_roles and model_has_permissions
         * table you can specify it here.
         */
        'model_morph_key' => 'model_type',
        'model_foreign_key' => 'model_id',
    ],

    /*
     * When set to true, the required permission names are added to the exception
     * message. This could be considered to be leaking information about
     * your application, so it defaults to false.
     */
    'display_permission_in_exception' => false,

    /*
     * When set to true, the required role names are added to the exception
     * message. This could be considered to be leaking information about
     * your application, so it defaults to false.
     */
    'display_role_in_exception' => false,

    /*
     * By default wildcard permission lookups are disabled.
     */
    'enable_wildcard_permission' => false,

    /*
     * By default, Laravel will use the `email` field for authentication.
     * If you would like to use a different field, you can specify it here.
     */
    'auth_model_keys' => [
        'email',
    ],

    /*
     * Cache configuration for permissions and roles.
     */
    'cache' => [
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        'key' => 'spatie.permission.cache',

        'store' => null, // default to the default cache store

        /*
         * When multiple tags are used for a cache, the cache is not
         * cleared automatically when one of the tags is updated.
         */
        'tags' => [],
    ],
];
