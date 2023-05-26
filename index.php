<?php
    $auth_page = true;

    $siteroot = __DIR__ . '/../../../..';
    include($siteroot . '/perch/core/inc/pre_config.php');
    include($siteroot . '/perch/config/config.php');

    include(PERCH_CORE . '/inc/loader.php');
    $Perch  = PerchAdmin::fetch();
    include(PERCH_CORE . '/inc/auth.php');
    
    // Check for logout
    if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
        $CurrentUser->logout();
    }

    // If the user's logged in, clone page, associated regions and items therein as well as related indices. Then send user to edit the new page
    if ($CurrentUser->logged_in()) {

        try {
            // Will need a form posting to this page with the page ID in a query string named: "id"
            if (!$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)) {
                 throw new \Exception('No valid page ID passed though POST vars');
            }
            if (!$titlepostfix = filter_input(INPUT_GET, 'renamepostfix', FILTER_SANITIZE_STRING)) {
                 throw new \Exception('There’s a problem with your renamepostfix');
            }
            $renamepostfix = preg_replace('/^-+|-+$/', '', strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $titlepostfix)));
            $renamepostfix = strpos($renamepostfix, '-') !== 0 ? '-' . $renamepostfix : $renamepostfix;
       
            $DB = PerchDB::fetch();

            $pagesFactory = new PerchContent_Pages();

            /** @var PerchContent_Page $page */
            $page = $pagesFactory->find($id);

            $uriparts = pathinfo($page->pagePath());

            $newFileName = $uriparts['filename'] . $renamepostfix;
            $originalIsIndex = $uriparts['filename'] == 'index' ? true : false;
            $originalIsHome = $originalIsIndex && $uriparts['dirname'] == '/' ? true : false;
            $newFilePath = !$originalIsHome ? $uriparts['dirname'] : '';
            $newFilePath .= ($uriparts['dirname'] != '/' ? '/' : '') . $newFileName . (isset($uriparts['extension']) ? '.' . $uriparts['extension'] : '');

            /** --------------
            Duplicating the physical PHP file on the server, if applicable.
            --------------- */
            // If page is a file and a file with the “new” name already exists under the given path, apply postfix to the new filename again
            if($uriparts['extension']):
                while (file_exists($siteroot . $newFilePath)) {
                    $newFileName .= $renamepostfix;
                    $titlepostfix .= $titlepostfix;
                    $newFilePath = !$originalIsHome ? $uriparts['dirname'] . '/' : '';
                    $newFilePath .= $newFileName . '.' . $uriparts['extension'];
                }

                if (!copy($siteroot . '/' . $page->pagePath(), $siteroot . $newFilePath)) {
                    throw new \Exception("Copying " . $page->pagePath() . " failed…\n");
                }
            endif;
            /** --------------
            END: Duplicating the physical PHP file on the server.
            --------------- */


            /** --------------
            Duplicating the relevant table rows in the database.
            --------------- */
            # 1. Page details
            // Get array from original page details
            $newPageDetails = $page->to_array();
            // Purge (pun intended) page id
            unset($newPageDetails['pageID']);


            // Set pageParentID to the original page’s id,
            // only if the page to be cloned is an index.php (pageParentID = 0, thus “false”). Otherwise keep pageParentID as is
            $newPageDetails['pageParentID'] = !$newPageDetails['pageParentID'] ? $id : $newPageDetails['pageParentID'];
            // Update filepath in new page details
            $newPageDetails['pagePath'] = $newFilePath;

            $newPageDetails['pageSortPath'] = '/' . $newFileName;
            // Update pageDepth in new page details if original is index
            $newPageDetails['pageDepth'] = $originalIsIndex && !$originalIsHome ? (int) $newPageDetails['pageDepth'] + 1 : $newPageDetails['pageDepth'];
            // Update tree position in new page details if original is index
            $newPageDetails['pageTreePosition'] = $originalIsIndex && !$originalIsHome ? $newPageDetails['pageTreePosition'] . '-001' : $newPageDetails['pageTreePosition'];
            // Update page title and navigation text in new page details
            $newPageDetails['pageTitle'] .= $titlepostfix;
            $newPageDetails['pageNavText'] .= $titlepostfix;
            // Hide new page from main navigation, to prevent it from showing up accidentally
            $newPageDetails['pageHidden'] = 1;
            // Update page modification time
            $newPageDetails['pageModified'] = date('Y-m-d H:i:s');

            // New page will not be associated with any navigation groups, to prevent it from showing up accidentally
            $allowedKeys = [
                'pageParentID',
                'pagePath',
                'pageTitle',
                'pageNavText',
                'pageNew',
                'pageOrder',
                'pageDepth',
                'pageSortPath',
                'pageTreePosition',
                'pageSubpageRoles',
                'pageSubpagePath',
                'pageHidden',
                'pageNavOnly',
                'pageAccessTags',
                'pageCreatorID',
                'pageModified',
                'pageAttributes',
                'pageAttributeTemplate',
                'pageTemplate',
                'templateID',
                'pageSubpageTemplates',
                'pageCollections',
            ];

            foreach($newPageDetails as $key => $value) {
                if(!in_array($key, $allowedKeys)) {
                    unset($newPageDetails[$key]);
                }
            }

            $newPage = $pagesFactory->create( $newPageDetails );
            if( is_object($newPage) ) { // created successfully
                $newPageID = $newPage->pageID();
            } else {
                echo 'Unable to create new page. Potential cause: Custom field ID starting with “page” or “template”.';
                exit;
            }

            # 2. Page regions
            $regionsFactory = new PerchContent_Regions();

            /** @var PerchContent_Regions $regions */
            $regions = $regionsFactory->get_by('pageID', $id);

            foreach($regions as $region) { // For every region in original page do…
                $newRegionDetails = $region->to_array();
                unset($newRegionDetails['regionID']);
                $newRegionDetails['pageID'] = $newPageID;
                $newRegionDetails['regionPage'] = $newFilePath;
                $newRegionDetails['regionRev'] = 1;
                $newRegionDetails['regionLatestRev'] = 1;
                $newRegionDetails['regionUpdated'] = date('Y-m-d H:i:s');
                 
                $newRegion = $regionsFactory->create( $newRegionDetails );
                if( is_object($newRegion) ) { // created successfully
                    // Collect new regions in array for cloning of item and indices
                    $regionIDs[$region->regionID()] = $newRegion->regionID();
                }
            }

            # 3. Page items and indices
            $itemsFactory = new PerchContent_Items();
            /** @var PerchContent_Item $item */
            foreach ($regionIDs as $oldRegionID => $newRegionID) {
                $items = $itemsFactory->get_by('regionID', $oldRegionID);
                $newItems = [];
                foreach($items as $item) { // For every content item in original page regions do…
                    // Get array from original item
                    $originalItemDetails = $item->to_array();
                    // Only update item in newItems array,
                    // if is not yet therein or update time of the item is newer than the one already available
                    if(empty($newItems[$originalItemDetails['itemID']]) || $originalItemDetails['itemUpdated'] > $newItems[$originalItemDetails['itemID']]['itemUpdated']) {
                        $newItems[$originalItemDetails['itemID']] = $originalItemDetails;
                    }
                }

                foreach($newItems as $newItemDetails) { // For every content item in newItems array do…
                    unset($newItemDetails['itemRowID']);
                    // Store original id for cloning of indices
                    $originalItemID = $newItemDetails['itemID'];
                    $originalItemRev = $newItemDetails['itemRev'];

                    $newMaxItemID = $itemsFactory->get_next_id();
                    $newItemDetails['itemID'] = $newMaxItemID;

                    $newItemDetails['regionID'] = $newRegionID;
                    $newItemDetails['pageID'] = $newPageID;
                    $newItemDetails['itemRev'] = 1;
                    $newItemDetails['itemUpdated'] = date('Y-m-d H:i:s');
                    
                    $newItem = $itemsFactory->create( $newItemDetails );
                    if( is_object($newItem) ) { // created successfully
                        $indices = $DB->get_rows("SELECT * FROM " . PERCH_DB_PREFIX . "content_index WHERE itemID = " . $originalItemID . " AND itemRev = " . $originalItemRev);
                        foreach ($indices as $index) {
                            $newIndexDetails = $index;
                            unset($newIndexDetails['indexID']);
                            $newIndexDetails['itemID'] = $newMaxItemID;        
                            $newIndexDetails['regionID'] = $newRegionID;
                            $newIndexDetails['pageID'] = $newPageID;
                            $newIndexDetails['itemRev'] = 1;

                            if( !$DB->insert(PERCH_DB_PREFIX . 'content_index', $newIndexDetails) ) {
                                throw new \Exception('Failed inserting index');
                            }

                        }
                    }
                }
            }
            /** --------------
             END: Duplicating the relevant table rows in the database
             --------------- */

            // send user to newly cloned page
            header("Location: ../../../core/apps/content/page/details/?id=" . $newPageID);

        } catch (\Exception $e) {
            //Redirect to an error page, whatever you want if something doesn't work out.
            PerchUtil::redirect('/404');
        }

    }