# FEUERWASSER ClonePage (frwssr_clonepage)
Field type to clone a (standard) page in [PerchCMS](https://grabaperch.com/).

## Installation

1. Download zip archive and extract locally.
1. Create a `frwssr_clonepage` folder in the `/perch/addons/fieldtypes/` folder of your perch install.
1. Copy the files `frwssr_clonepage.class.php`, `index.php`, and `init.js` to the `/perch/addons/fieldtypes/frwssr_clonepage` folder.

## Usage
In a perch template, you can use this field type as follows:
```html
<perch:content id="clone" type="frwssr_clonepage" suppress>
```

### Attributes
- *buttontext* - Customize the text on the button. Defaults to “Copy item”.
- *renamefield* - Pass the id of a heading field (or similar) to alter said field in the clone.
- *renamepostfix* - Customize the text appended to the field passed with `renamefield`. Will do nothing, if `renamefield` is not present. Defaults to “ (Copy)”.

### Example
```html
<perch:content id="clone" type="frwssr_clonepage" buttontext="Make a copy of this awesome page" renamefield="itemheading" renamepostfix="—copy" suppress>
```

### Notes
- Use `suppress` on the `frwssr_clonepage` field to make sure it doesn’t show up in your website.
- If you are creating a slug of any field in the template, the slug field in the cloned item will hold the exact same value. You need to update that manually. (Unfortunately, there is no way to automate this.)


# License
This project is free, open source, and GPL friendly. You can use it for commercial projects, for open source projects, or for almost whatever you want, really.

# Donations
This is free software, but it took some time to develop. If you use it, please let me know—I live off of positive feedback…and chocolate.
If you appreciate the fieldtype and use it regularly, feel free to [buy me some sweets](https://paypal.me/nlsmlk).

# Issues
Create a GitHub Issue: https://github.com/frwssr/frwssr_clonepage/issues or better yet become a contributor.

Developer: Nils Mielke (nils.m@feuerwasser.de, [@nilsmielke](https://twitter.com/nilsmielke)) of [FEUERWASSER](https://www.feuerwasser.de)
