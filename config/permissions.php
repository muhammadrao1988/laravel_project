<?php

return array(
    array("module" => "User", "key" => "create", "name" => "Add Users"),
    array("module" => "User", "key" => "read", "name" => "View Users"),
    array("module" => "User", "key" => "update", "name" => "Edit Users"),
    array("module" => "User", "key" => "delete", "name" => "Delete Users"),

    array("module" => "Role", "key" => "create", "name" => "Add Roles"),
    array("module" => "Role", "key" => "read", "name" => "View Roles"),
    array("module" => "Role", "key" => "update", "name" => "Edit Roles"),
    array("module" => "Role", "key" => "delete", "name" => "Delete Roles"),


    /*array("module" => "Menu", "key" => "read", "name" => "View Menus"),
    array("module" => "Menu", "key" => "update", "name" => "Edit Menus"),*/

   /* array("module" => "Configuration", "key" => "read", "name" => "View Configuration"),
    array("module" => "Configuration", "key" => "update", "name" => "Edit Configuration"),*/

    array("module" => "Giftee", "key" => "create", "name" => "Add Giftee"),
    array("module" => "Giftee", "key" => "read", "name" => "View Giftee"),
    array("module" => "Giftee", "key" => "update", "name" => "Edit Giftee"),
    array("module" => "Giftee", "key" => "delete", "name" => "Delete Giftee"),

    array("module" => "GiftGuide", "key" => "create", "name" => "Add Gift Guide"),
    array("module" => "GiftGuide", "key" => "read", "name" => "View Gift Guide"),
    array("module" => "GiftGuide", "key" => "update", "name" => "Edit Gift Guide"),
    array("module" => "GiftGuide", "key" => "delete", "name" => "Delete Gift Guide"),

    array("module" => "Categories", "key" => "create", "name" => "Add Categories"),
    array("module" => "Categories", "key" => "read", "name" => "View Categories"),
    array("module" => "Categories", "key" => "update", "name" => "Edit Categories"),
    array("module" => "Categories", "key" => "delete", "name" => "Delete Categories"),

    array("module" => "States", "key" => "create", "name" => "Add State Tax"),
    array("module" => "States", "key" => "read", "name" => "View State Tax"),
    array("module" => "States", "key" => "update", "name" => "Edit State Tax"),
    array("module" => "States", "key" => "delete", "name" => "Delete State Tax"),

    array("module" => "CartOrders", "key" => "update", "name" => "Manage Cart Orders"),
    array("module" => "ContributionOrders", "key" => "update", "name" => "Manage Contribution Orders"),
    array("module" => "GiftOfferOrders", "key" => "update", "name" => "Manage Gift Offer Orders"),

    /*MASTER MENUS*/
    array("module" => "Menu", "key" => "admin/giftee", "name" => "Show Giftee Menu"),
    array("module" => "Menu", "key" => "admin/giftguide", "name" => "Show Gift Guide Menu"),
    array("module" => "Menu", "key" => "admin/categories", "name" => "Show Category Menu"),
    array("module" => "Menu", "key" => "orders", "name" => "Show Order Menu"),
    /*MASTER MENUS*/

    /*APPLICATION WIDE PERMISSIONS*/
    array("module" => "System", "key" => "notification", "name" => "Show Notifications"),
);
