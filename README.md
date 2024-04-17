
<h1 align="center">
  <a href="https://github.com/khode4li/wordino-v2"><img src="wordino.png" alt="Markdownify" width="1000"></a>
</h1>

<p align="center">
<a href="#Key-Features">Key Features</a> - <a href="#Installation">Installation</a> - <a href="#Https">Https</a> - <a href="#Configuration">Configuration</a> - <a href="#How-To-Use">How To Use</a> - <a href="#Warning">Warning</a> - <a href="#Todo-List">Todo List</a>
</p>

---
## Key Features
* Seamless integration with third-party tools
* Cross-platform compatibility
* Role-based permissions
* Effortless wordlist organization
* Bulk import and export functionality
* Automatic duplicate detection and removal
* Collaborative features for team use

## Installation

```bash
git clone https://github.com/khode4li/wordino-v2
cd wordino-v2
php install-1.php
```
The first installation file (`install-1.php`) will install PHP dependencies and prompt you to enter your database password.
```bash
docker compose up -d
docker exec -it wordino-v2-php-apache-1 php install-2.php
```
The second installation file, which you should run inside the container, first initializes the database and then will ask you about your username and password. Remember that the first user is always the owner, and the owner has access to all permissions. So, be careful!


## Https
1. Begin by adding a new domain to your Cloudflare dashboard.
2. Next, create a new subdomain pointed to your IP with CDN enabled.
3. Proceed to the `Rules` section and navigate to `Origin Rules` to add a new rule.
4. Select `Custom filter expression`, set the `Field` to `Hostname`, the `Operator` to `equals`, and input your subdomain name as the value.
5. Then, click on `Rewrite to...` and specify the application port.
6. Once completed, navigate to your SSL/TLS settings and activate the `Flexible` option.
7. Finally, open your subdomain, and voilà! You're all set.


## Configuration
### install-1.php
In `install-1.php`, this script automates the installation of project dependencies. Subsequently, it prompts for the database password and updates it within both the `docker-compose.yml` file and `config.php`.
### install-2.php
Within the `install-2.php` file, intended for execution within the Docker container, user will be prompted for the username and password of the owner account. Subsequently, the script initializes the database and adds the account to it.
### `system/config.php`
This file contains vital configurations, including database connection details, Redis connection settings, salt value, user login duration, and memory limits. Typically, adjustments to database connection, Redis configuration, salt value, and login duration aren't necessary. However, in cases where importing large wordlists from URLs is required, it's advisable to increase the memory limits.



## How To Use
I'll go straight to the point since I'm not sure where to begin. Each endpoint requires a permission, and the owner has all these permissions, which are not changeable because they are hard-coded. You can customize these permissions according to your needs by creating a new role. For example, you can specify that the "visitor" role should not have access to sections related to adding word lists, modifying word lists, or groups, but only to read word lists. This idea is useful for relatively large groups where you need to restrict some access for certain team members like newcomers.

Below is the list of permissions and the actions each permission can perform:

| Permission name            | Ability                                                                            |
|----------------------------|------------------------------------------------------------------------------------|
| createGroup                | The capability to create new groups.                                               |
| deleteGroup                | The capability to delete groups.                                                   |
| addWordlistToGroup         | The capability to add wordlists to groups.                                         |
| removeWordlistFromGroup    | The capability to remove wordlists from groups.                                    |
| seeAllGroups               | The capability to view a list of groups.                                           |
| seeWordlistsOfAGroup       | The capability to view the wordlists of a group.                                   |
| seeWordsOfAGroup           | The capability to view the words of a group (from all wordlists within the group). |
| seeGroupsWordsCount        | The capability to view the number of words within a group.                         |
| seeAllRoles                | The capability to view all roles within the system.                                |
| seeAllPerms                | The capability to view all permissions.                                            |
| createNewRole              | The capability to create a new role.                                               |
| editRolePermissions        | The capability to edit permissions for a role.                                     |
| deleteRolePermissions      | The capability to delete a role.                                                   |
| addNewUser                 | The capability to create a new user.                                               |
| seeAllUsers                | The capability to view all users.                                                  |
| changeOtherUsersRole       | The capability to change other users' roles.                                       |
| seeOtherUsersPermissions   | The capability to view other users' permissions.                                   |
| deleteOtherUsersAccount    | The capability to delete other users' accounts.                                    |
| addNewWordToWordlists      | The capability to add new words to the wordlist.                                   |
| createWordlist             | The capability to create a new wordlist.                                           |
| deleteWordlist             | The capability to delete a wordlist.                                               |
| seeWordlistWords           | The capability to view all words from a wordlist.                                  |
| seeAllWordlists            | The capability to view all wordlists.                                              |
| seeWordlistsWordsCount     | The capability to view the number of words in a wordlist.                          |
| addMultipleWordToWordlists | The capability to add multiple words to a wordlist.                                |
| addWordsToWordlistFromUrl  | The capability to import words from a URL to your wordlist.                        |

After roles and permissions, we come to the main section which is wordlists. You can create various wordlists and add many words to them, but the system we've built should have some additional features for greater convenience, otherwise, what's the point, right? :)

In addition to single words, you can enter many words into a wordlist (don't forget to send the separator parameter, which is available in the example in Postman). Besides, you can import a complete wordlist via URL into your own wordlist, which is very useful for rapid wordlist expansion. Moving on from wordlists, let's talk about groups, which is my favorite part. You can categorize your wordlists into groups. But why does this matter? Imagine you have different wordlists like "xss-parameters," "redirect-parameters." Sometimes, you only need to test redirect-parameters in certain places, no worries, you can easily select and download that wordlist. But sometimes you want to test all parameters, in which case you can download the entire parameters group at once (of course, you must have added parameter wordlists to it beforehand xD).

If you have any new ideas that you think would be useful, I'd be happy to hear from you so I can add them.

## Warning
* Caution: The 'import from URL' functionality contains a SSRF vulnerability. Exercise discretion when granting permissions for its use.
* Beware: Granting the permissions `createNewRole` and `editRolePermissions` enables users to create roles with permissions they lack. Exercise caution when assigning these permissions.

## Todo List
- [ ] I can implement checks in the models, such as verifying whether a wlist exists or other conditions.
- [ ] I can inject the `x` model into the `x` controller to enhance usability.
- [ ] Utilize Redis for rate-limiting the login endpoint effectively.
- [ ] I can refactor the process of cleaning data returned from the database into a single function. For example: {`"username": "x", "0": "x"`}.
- [ ] Transform certain arrays into objects for increased safety and better code organization.
- [ ] Is there a faster way for managing wordlists?
