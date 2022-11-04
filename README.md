# Dynamic-CallerID-Set-in-Asterisk-based-PBX-like-Elastix-4-or-Issabel-4-
**Dynamic CallerID Set in Asterisk based PBX like Elastix 4 or Issabel 4**

1. Keep the Source Code to this directory: /var/www/html/dynamic_callerid

2. Mysql root user and password set to this file: `config.php`

3. Mysql script to insert a table called `did_numbers` in the database `asterisk`:
```
use asterisk;
CREATE TABLE `did_numbers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route_name` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `route_cid` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `did` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flag` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
```

4.  Elastix Developer Module [This step is for Elastix 4 PBX if the module isn't installed elastix-developer]:
To install the module `elastix-developer` you'll need to download the old rpm file as I didn't found the new one from the following URL. 
```
cd /usr/src/ 
wget http://elastix.adaptixnetworks.com/index.php?dir=2.5/extras/arm/RPMS/&file=elastix-developer-2.3.0-5.noarch.rpm 
rpm -i elastix-developer-2.3.0-5.noarch.rpm
```
5. For Issabel 4 PBX, you'll need to install the "issabel-developer" module if the module isn't installed.
```
yum install issabel-developer
```
6. Add a front-end Menu for GUI:

Login as admin in GUI and then go to menu "Developer > Build Module " [Now create a custom module like below]
```
Module Name: Dynamic CallerID
Your Name: Write your name
Your Email: Write your email

Group Permission: Select Adminitrator, Operator
Module Level: Select Level 2 
Level 1 Parent Exists: Select Yes 
Level 1 Parent: Select PBX 

Module Type: Framed
URL: https://your_server_domain.com_or_ip_address/dynamic_callerid 
```
Click on Save. It will redirect to your newly created module.


7. Override the Asterisk Dialplan with the following script: Edit the file `/etc/asterisk/extensions_custom.conf`

;---------------
```
[macro-dialout-trunk-predial-hook] 
;Outbound Call With a Dynamic Caller ID Number 
exten => s,1,NoOp(The caller id name is: ${CALLERID(name)}) 
exten => s,n,Set(callerid_new=${SHELL(php /var/www/html/dynamic_callerid/did_numbers.php) ${CALLERID(num)}}) 
exten => s,n,NoOp(The new caller id number is: ${callerid_new} -- ${CALLERID(num)}) 
exten => s,n,Set(CALLERID(all)=${IF($["${callerid_new}"=""]?${CALLERID(num)}:${callerid_new})})
exten => s,n,MacroExit()
```

;----------------------

8. After save the script, run this command to reload the asterisk dialplan to effect new callerid.
```
asterisk -rx"dialplan reload"

```

Now go to PBX GUI :: Click on PBX > Dynamic CallerID . 

Notes:
1. You can add multiple CallerID or DID numbers for the selected Outbound Routes using comma separated.
2. Outbound Route CID can't be blank and should be unique and Override extension must be checked.
3. Dynamic CallerID will set as a round-robin method.

![alt text](https://github.com/amir-eee/Dynamic-CallerID-Set-in-Asterisk-based-PBX-like-Elastix-4-or-Issabel-4-/blob/main/Dynamic%20CID%20Number%20List.PNG)
![alt text](https://github.com/amir-eee/Dynamic-CallerID-Set-in-Asterisk-based-PBX-like-Elastix-4-or-Issabel-4-/blob/main/Dynamic%20CID%20Create.PNG)
