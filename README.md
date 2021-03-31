# FEUERWASSER ClonePage (frwssr_clonepage)
Field type to clone a (somewhat standard) page and its contents in [PerchCMS](https://grabaperch.com/).

## Installation

1. Download zip archive and extract locally.
1. Create a `frwssr_clonepage` folder in the `/perch/addons/fieldtypes/` folder of your perch install.
1. Copy the files `frwssr_clonepage.class.php`, `index.php`, and `init.js` to the `/perch/addons/fieldtypes/frwssr_clonepage` folder.

## Usage
In a Perch attributes template (e.g. `perch/templates/pages/attributes/seo.html`), you can use this field type as follows:
```html
<perch:pages id="clone" type="frwssr_clonepage" suppress>
```

### Attributes
- *buttontext* - Customize the text on the button. Defaults to “✌️ Clone page ⚠️” (—the emoji trying to signify the *danger zone* character of the button.)
- *buttonbg* - Customize the background of the button. Defaults to `slategray`. You might get fancy with something like `buttonbg="linear-gradient(to top right, teal, tomato)"`, too. Impress your Perch users!
- *renamepostfix* - Customize the text appended to the *Page title* and the *Navigation text*. Defaults to “ (Copy)”. A slugified version of the `renamepostfix` will be appended to the clone’s filename, too.

### Example
```html
<perch:pages id="clone" type="frwssr_clonepage" buttontext="Make a copy of this awesome page" buttonbg="linear-gradient(to top right, teal, tomato)" renamepostfix="—copy" suppress>
```

### Notes
- Use `suppress` on the `frwssr_clonepage` field to make sure it doesn’t show up in your website.
- The clone will be placed in the same folder as the original file.
- The clone will be hidden from the main navigation and will not be associated with any navigation groups, to prevent it from showing up in the wrong place.
- The filename will be a product of the original page’s filename and a (predefined or custom) postfix. To keep URLs clean, you will have to use the standard processes in Perch to rename and move the file.
- If you are creating a slug in any of the content items, the slug field in the cloned page’s item will hold the exact same value as the original. You need to update that manually. (Unfortunately, there is no way to automate this.)
- Cloning pages will work best on pages, utilising a generic master page with region names like `banner`, `contents`, and such. Otherwise you might end up with multiple pages holding a region called `news`, for instance.
- This fieldtype was developed under Perch (Standard) Version 3.1.7 on a server running PHP 7.4.x.  
**Use at own risk!**


# License
This project is free, open source, and GPL friendly. You can use it for commercial projects, for open source projects, or for almost whatever you want, really.

# Donations
This is free software, but it took some time to develop. If you use it, please let me know—I live off of positive feedback…and chocolate.
If you appreciate the fieldtype and use it regularly, feel free to [buy me some sweets](https://paypal.me/nlsmlk).

# Issues
Create a GitHub Issue: https://github.com/frwssr/frwssr_clonepage/issues or better yet become a contributor.

Developer: Nils Mielke (nils.m@feuerwasser.de, [@nilsmielke](https://twitter.com/nilsmielke)) of [FEUERWASSER](https://www.feuerwasser.de)