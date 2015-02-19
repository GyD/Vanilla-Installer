<?php if (!defined('APPLICATION')) exit();

// EnabledApplications
$Configuration['EnabledApplications']['Yaga'] = 'yaga';

// EnabledLocales
$Configuration['EnabledLocales']['vf_fr'] = 'fr';

// EnabledPlugins
$Configuration['EnabledPlugins']['VanillaStats'] = TRUE;
$Configuration['EnabledPlugins']['vanillicon'] = TRUE;
$Configuration['EnabledPlugins']['Flagging'] = TRUE;
$Configuration['EnabledPlugins']['YagaRankInMeta'] = TRUE;
$Configuration['EnabledPlugins']['Parsedown'] = TRUE;

// Garden
$Configuration['Garden']['Title'] = 'Exemple';
$Configuration['Garden']['InputFormatter'] = 'Markup';

// Plugins
$Configuration['Plugins']['Flagging']['UseDiscussions'] = '1';
$Configuration['Plugins']['Flagging']['CategoryID'] = '3';
$Configuration['Plugins']['BulkInvite']['InsertUserID'] = 2;
$Configuration['Plugins']['Parsedown']['BreaksEnabled'] = TRUE;

// Vanilla
$Configuration['Vanilla']['Comment']['MaxLength'] = '16000';