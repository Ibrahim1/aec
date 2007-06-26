<?php
// Copyright (C) 2006-2007 David Deutsch
// All rights reserved.
// This source file is part of the Account Expiration Control Component, a  Joomla
// custom Component By Helder Garcia and David Deutsch - http://www.globalnerd.org
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// Please note that the GPL states that any headers in files and
// Copyright notices as well as credits in headers, source files
// and output (screens, prints, etc.) can not be removed.
// You can extend them with your own credits, though...
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.

?>

/*
SQLyog Community Edition- MySQL GUI v5.20
Host - 5.0.24a-community : Database - joomla
*********************************************************************
Server version : 5.0.24a-community
*/


SET NAMES utf8;

SET SQL_MODE='';

/* create database if not exists `joomla`;  */

USE `Your Joomla database Here`;

SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

/*Table structure for table `jos_vm_xt_bridge_shopper_group` */

DROP TABLE IF EXISTS `jos_vm_xt_bridge_shopper_group`;

CREATE TABLE `jos_vm_xt_bridge_shopper_group` (
  `vm_shopper_group` int(11) NOT NULL,
  `acl_group_id` int(11) NOT NULL,
  PRIMARY KEY  (`acl_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci ROW_FORMAT=DYNAMIC COMMENT='Relate VM Shooper Group to Core ACL Group';

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;

_________________________

DELIMITER $$

DROP TRIGGER `joomla`.`jos_users_bu_sync_shop_grp`$$

create trigger `jos_users_bu_sync_shop_grp` BEFORE UPDATE on `jos_users` 
for each row BEGIN
    IF OLD.gid <> NEW.gid THEN
        BEGIN
            /* Make sure its a group associated with Shopper group - might not be*/
            select  count(*)
            from    jos_vm_xt_bridge_shopper_group
            where   acl_group_id = NEW.gid
            into    @sync_required;
            
            if ( @sync_required >= 1 ) then
                BEGIN
                
                    /* Verify if the customer acc. already exists in VM's table */
                    select  count(*)
                    from    jos_vm_user_info
                    where   user_id = NEW.id
                    into    @user_acc_exists;
                    
                    if ( @user_acc_exists = 0 ) THEN
                        BEGIN
                        
                            /* Insert new entry into  jos_vm_user_info */
                            INSERT INTO jos_vm_user_info
                                    (
                                    user_info_id,
                                    user_id,
                                    address_type,
                                    last_name,
                                    first_name,
                                    address_1,
                                    city,
                                    state,
                                    country,
                                    zip,
                                    user_email,
                                    cdate
                                    )
                            VALUES(
                                    md5(
                                        date_format( 
                                                date_add( sysdate(), INTERVAL FLOOR( 1 + (RAND() * 998)) MICROSECOND),
                                                "%Y%m%d%H%i%s%f"
                                	       ) 
                                       ),
                                    NEW.id,
                                    "BT",
                                    SUBSTRING( NEW.name, (LENGTH( NEW.name ) - LOCATE(' ', REVERSE( NEW.name ) ) )+2),
                                    left(NEW.name, INSTR(NEW.name,' ')),
                                    "Preencha...",
                                    "Preencha...",
                                    "Estado/Distrito...",
                                    "PRT",
                                    "Preencha",
                                    NEW.email,
                                    UNIX_TIMESTAMP()
                            );
                            /* Insert Entry into jos_vm_shopper_vendor_xref */
                            INSERT INTO jos_vm_shopper_vendor_xref
                                            (
                                            user_id,
                                            vendor_id,
                                            shopper_group_id
                                            )
                            SELECT  NEW.id,
                                    1,
                                    b.vm_shopper_group
                            FROM    jos_vm_xt_bridge_shopper_group b
                            WHERE   b.acl_group_id = NEW.gid;
                        END;
                    ELSE        /* User acc in VM already exists*/
                        BEGIN
                            /* Since the customer already exists in VM, we don't presume entry in
                                jos_vm_shopper_vendor_xref exists, so just need to
                                verify if exists and accordingly insert or update the shopper group 
				associated with the ACL Group
                            */
				select  count(*)
				from    jos_vm_shopper_vendor_xref
				where   user_id = NEW.id
				into    @shopper_grp_xref_exists;

				SELECT  b.vm_shopper_group
				FROM    jos_vm_xt_bridge_shopper_group b
				WHERE   b.acl_group_id = NEW.gid
				INTO    @updated_shopper_group;

				if ( @shopper_grp_xref_exists = 0 ) THEN
					BEGIN
						/* Insert Entry into jos_vm_shopper_vendor_xref */
						INSERT INTO jos_vm_shopper_vendor_xref
								(
								user_id,
								vendor_id,
								shopper_group_id
								)
						VALUES ( NEW.id, 
							 1, 
							 @updated_shopper_group );
					END;
				ELSE
					BEGIN
						UPDATE  jos_vm_shopper_vendor_xref
						SET     shopper_group_id = @updated_shopper_group
						WHERE   user_id = NEW.id;
					END;
				END IF;
                        END;
                    END IF;  /* ( @user_acc_exists = 0 ) */
                END;
            END IF; /* (sync >= 1 ) */
        END;
    END IF; /* OLD.gid <> NEW.gid */
END;
$$

DELIMITER ;

_____________________

Account Expiration Control (AEC) to Virtuemart Shopper Group (VM) Bridge

Version : 0.1 - Alpha : 21-Dec-2006
	  0.2 - Alpha : 29-Mar-2007, improved process if VM account data already exists

GOAL:
-----

In order to support different shopper levels (in VirtueMart), each with its own special pricing conditions, one has to use the "Shopper Group" functionality in VM.

The next problem consists in selling subscriptions to the site, whereby different
subscription plans should be associated to specific "Shopper Group" so that upon
sucessfull subscription and the following login, the user would automatically
see the new prices for his particular subscription level (i.e. Shopper Group).

PRE-REQUISITES:
---------------

The following components & Modules must be previusly installed always verifying
appropriate compatibility among all components:


a) Component JACLPlus - Version 1.0.11
This component allows you to create new User Groups and/or new Access Levels in Joomla!

b) Account Expiration Control Component - Version 0.12.3.* (tested with 0.12.3.47)
Account and subscription management component & login module


Bridge Composition / INSTALLATION:
----------------------------------

1 - Backup your Test environment !! and DO NOT install this in your production
    environment !

The Bridge consists of a new database table and a trigger, just run the
appropriate scripts on your database to create the objects

The script for the table creation is:
 - SQL_Create_Bridge_Table.sql

The script for the trigger cration on the jos_users table is:
 - SQL_Create_Trigger_jos_acctexp_subscr_bu_v2.sql
 
 
BASIC SETUP:
--------------------

Basic configuration is:

1 - Create your Shopper Groups in VM
2 - Create a new "Subscriber" access level in JACL "Access Level Manager"
3 - Create User Groups in JACL's User Group Manager, similar to the Shopper Groups
4 - Create the subscription Plans in AEC Component (sh)
    4.a - Each subscription plan should correspond to a different UserGroup created in JACL

5 - Fill the newly created "bridge" table with the required "JACL User Group ID"
    and "VM Shopper Group ID" pairs, i.e. a given JACL User Group corresponds
    to a specific Shopper Group.
    5.a - The IDs for each group must be checked directly in the database
    5.b - There is no front-end for managing the bridge table, mustalso be done
          directly in the database via your MySQL front-end

That's it !

HOW IT WORKS
-------------

As long as AEC manages the subscriptions, the databse trigger just "sits waiting" for the
"Group ID" field in the joomla user table to be changed.

When a visitor requests account creation, the AEC component takes over and
eventually changes the "Group ID" for the new one corresponding to the new
subscription chosen and paid for by the user.

At that time, the tigger verifies if that users VM account exists, if not, it
creates one with some default values and places the user in the appropriate
Shopper Group (after getting the appropriate value from the bridge table).

/* If the user account already exists, then it only updates the "Shopper Group"
   leaving all VM user account data unchanged. 

-- Superceded by following paragraph  -- 
*/

If the user account already exists, then it will verify if the shopper_group data is also filled, and will either create or update the existing entry accordingly leaving 
all VM user account data unchanged. 


WHAT WORKS
---------

The following worked in my test system:

1 - Making a purchase without any user registration.
    The user gets the prices from the default shopper group
    
2 - User account creation from login module (for ex.: CBlogin), Subscription to site
    and automatic "Shopper Group" assignment

3 - Account / ShopperGroup downgrade (resulting from account expiration)

4 - VM's User Account editing from the website shows no problems

5 - Subscription upgrades


WHAT DOESN'T WORK - investigation in course
------------------
6 - Shopper Group Assignment by user account created from VM - this happens if account creation at cart checkout stage is not routed to AEC's account registration process.