<?php
/**
 * Active Ecommerce Backup And Restore manager: ajax/backup_restore.php . this is rout file
 *
 * Generate short sharing link
 *
 * PHP version >= 5.3
 *
 * @category  PHP
 * @package   Active Ecommerce Backup And Restore manager Addon
 * @author    Reza Kia <kia@aryaclub.com>
 * @copyright 2023 Reza Kia
 * @license   Apache 2.0
 * @link      http://aryaclub.com/
 */

/*
|--------------------------------------------------------------------------|
| Backup And Restore Routes                                                |
|--------------------------------------------------------------------------|
|                                                                          |
| Here is where you can register admin routes for your application. These  |
| routes are loaded by the RouteServiceProvider within a group which       |
| contains the "web" middleware group. Now create something great!         |
|--------------------------------------------------------------------------|
*/

use App\Http\Controllers\BackupController;

//me add
Route::controller(BackupController::class)->group(function () {
    Route::get('/backups', 'index')->name('backups')->middleware(['auth', 'admin', 'prevent-back-history']);;
    Route::post('/backups/storexx', 'storexx')->name('backups.storexx');
    Route::post('/backups/download/backups', 'downloadBackups')->name('backups.download.backups');
    Route::post('/backups/download/restore', 'restoreBackups')->name('backups.download.restore');
    Route::delete('/backups/download/delete', 'deleteBackups')->name('backups.download.delete');
    Route::post('/backups/download/uploadviaftp', 'uploadviaftpBackups')->name('backups.download.uploadviaftp');
    Route::post('/bulk-backup-delete', 'bulk_backup_delete')->name('bulk-backup-delete');
    Route::post('/backups/shorten', 'shorten')->name('backups.shorten');
    Route::post('/backups/shortencron', 'shortencron')->name('backups.shortencron');
    Route::post('/backups/sendfilestoemail', 'sendfilestoemail')->name('backups.sendfilestoemail');
    Route::get('/backups/viewfilefromlink/{linkkey}', 'viewfilefromlink')->name('backups.viewfilefromlink');
    Route::get('/backups/captcha/{tmp}', 'captcha')->name('backups.captcha');
    Route::get('/backups/pelpelak1/{countfiles_sh_share_myfile}', 'downloadFileFromLink1')->name('backups.pelpelak1');
    Route::post('/backups/editsharelink', 'editBackupsShareLinkInfo')->name('backups.editsharelink');
    Route::post('/backups/share_modal', 'share_modal')->name('backups.share_modal');
    Route::post('/backups/storecronbackuptype', 'storecronbackuptype')->name('backups.storecronbackuptype');
    Route::get('/backups/generatejson', 'generatejson')->name('backups.generatejson');
    Route::post('/backups/editcronjobtask', 'editCronjobTaskLinkInfo')->name('backups.editcronjobtask');
    Route::get('/backups/deletecronjobtask/{linkkey}', 'deleteCronjobTaskLinkInfo')->name('backups.deletecronjobtask');
    Route::get('/backups/executecronjobfromlink/{test}/{linkkey}', 'executecronjobfromlink')->name('backups.executecronjobfromlink');
});
Route::get('/backups/cronjobbackup/{test}/{linkkey}', [BackupController::class, 'cronjobbackup'])->name('backups.cronjobbackup');
Route::any('/backups/cronjobbackup2', [BackupController::class, 'cronjobbackup2'])->name('backups.cronjobbackup2');

//me end
