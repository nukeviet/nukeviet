import re
import os
import sys

def process_file(file_path):
    # Normalize path for the current OS
    normalized_path = os.path.normpath(file_path)
    if not os.path.exists(normalized_path):
        print(f"File not found: {normalized_path}")
        return
    
    try:
        with open(normalized_path, 'r', encoding='utf-8') as f:
            content = f.read()
    except Exception as e:
        print(f"Error reading {normalized_path}: {e}")
        return
    
    # Regex to match $nv_Request->get_title with 4 arguments where the 4th is 0 or 1
    # Arguments pattern: [^,\(\)]+ handles simple cases. 
    # Strings can have commas inside, but usually get_title args are simple.
    # Refined pattern to be more robust:
    # Match: $nv_Request->get_title(arg1, arg2, arg3, 0|1)
    # We look for the 4th argument being 0 or 1.
    
    # This pattern matches $nv_Request->get_title(..., ..., ..., 0|1)
    # where the first 3 arguments don't contain a closing parenthesis or a comma that is not inside quotes.
    # For simplicity in this environment, we'll use a regex that handles common NukeViet patterns.
    pattern = r'(\$nv_Request->get_title\s*\(\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*,\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*,\s*(?:[^,\'"]*|\'[^\']*\'|"[^"]*")\s*),\s*([01])\s*\)'
    
    new_content = re.sub(pattern, r'\1)', content)
    
    if new_content != content:
        try:
            with open(normalized_path, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f"Updated: {normalized_path}")
        except Exception as e:
            print(f"Error writing {normalized_path}: {e}")
    else:
        # print(f"No changes: {normalized_path}")
        pass

files_report = """
src\admin\authors\add.php:162
src\admin\authors\config.php:148
src\admin\authors\config.php:149
src\admin\authors\config.php:150
src\admin\authors\config.php:257
src\admin\authors\del.php:92
src\admin\authors\suspend.php:71
src\admin\database\setting.php:27
src\admin\emailtemplates\categories.php:151
src\admin\language\main.php:132
src\admin\language\main.php:133
src\admin\modules\change_alias.php:43
src\admin\modules\change_custom_name.php:43
src\admin\modules\edit.php:147
src\admin\modules\edit.php:155
src\admin\modules\edit.php:156
src\admin\modules\edit.php:157
src\admin\modules\edit.php:158
src\admin\modules\edit.php:159
src\admin\modules\edit.php:161
src\admin\modules\setup.php:30
src\admin\modules\setup.php:31
src\admin\modules\vmodule.php:31
src\admin\modules\vmodule.php:33
src\admin\modules\vmodule.php:34
src\admin\seotools\pagetitle.php:27
src\admin\seotools\sitemapPing.php:190
src\admin\settings\cronjobs.php:172
src\admin\settings\ftp.php:33
src\admin\settings\ftp.php:35
src\admin\settings\ftp.php:36
src\admin\settings\ftp.php:83
src\admin\settings\ftp.php:85
src\admin\settings\ftp.php:86
src\admin\settings\ftp.php:87
src\admin\settings\main.php:44
src\admin\settings\main.php:52
src\admin\settings\main.php:62
src\admin\settings\main.php:64
src\admin\settings\main.php:80
src\admin\settings\main.php:125
src\admin\settings\security.php:384
src\admin\settings\smtp.php:494
src\admin\settings\smtp.php:495
src\admin\settings\smtp.php:496
src\admin\settings\system.php:90
src\admin\settings\system.php:126
src\admin\settings\system.php:173
src\admin\settings\system.php:199
src\admin\settings\system.php:225
src\admin\settings\system.php:235
src\admin\settings\system.php:237
src\admin\themes\blocks.php:58
src\admin\themes\blocks_change_pos.php:17
src\admin\themes\block_content.php:117
src\admin\themes\block_content.php:127
src\admin\themes\block_content.php:128
src\admin\themes\block_content.php:161
src\admin\themes\block_content.php:163
src\admin\themes\deletetheme.php:16
src\admin\upload\renameimg.php:54
src\admin\upload\upload.php:230
src\admin\webtools\statistics.php:31
src\admin\webtools\statistics.php:39
src\admin\webtools\statistics.php:40
src\admin\webtools\statistics.php:49
src\includes\core\admin_login.php:673
src\includes\core\admin_login.php:727
src\index.php:237
src\install\index.php:53
src\install\index.php:54
src\install\index.php:55
src\install\index.php:56
src\install\index.php:700
src\install\index.php:701
src\install\index.php:712
src\install\index.php:713
src\install\update.php:1542
src\install\update.php:1543
src\install\update.php:1544
src\install\update.php:1545
src\modules\banners\funcs\addads.php:24
src\modules\banners\funcs\addads.php:25
src\modules\banners\funcs\addads.php:26
src\modules\banners\funcs\addads.php:27
src\modules\comment\funcs\post.php:106
src\modules\comment\funcs\post.php:133
src\modules\contact\funcs\main.php:120
src\modules\contact\funcs\main.php:131
src\modules\contact\funcs\main.php:152
src\modules\contact\funcs\main.php:161
src\modules\feeds\blocks\global.rss.php:81
src\modules\menu\admin\blocks.php:76
src\modules\menu\admin\main.php:113
src\modules\menu\admin\main.php:128
src\modules\menu\admin\main.php:129
src\modules\menu\admin\main.php:130
src\modules\menu\admin\main.php:133
src\modules\news\admin\authors.php:207
src\modules\news\admin\cat.php:83
src\modules\news\admin\cat.php:84
src\modules\news\admin\cat.php:85
src\modules\news\admin\content.php:703
src\modules\news\admin\content.php:713
src\modules\news\admin\content.php:773
src\modules\news\admin\groups.php:144
src\modules\news\admin\groups.php:145
src\modules\news\admin\setting.php:115
src\modules\news\admin\setting.php:116
src\modules\news\admin\setting.php:136
src\modules\news\admin\setting.php:137
src\modules\news\admin\sourceajax.php:16
src\modules\news\admin\sources.php:124
src\modules\news\admin\topicajax.php:16
src\modules\news\admin\topics.php:128
src\modules\news\admin\topics.php:129
src\modules\news\funcs\content.php:114
src\modules\news\funcs\content.php:357
src\modules\news\funcs\content.php:358
src\modules\news\funcs\content.php:365
src\modules\news\funcs\search.php:151
src\modules\news\funcs\search.php:161
src\modules\page\admin\content.php:80
src\modules\page\admin\content.php:85
src\modules\seek\funcs\main.php:40
src\modules\users\admin\config.php:206
src\modules\users\admin\config.php:217
src\modules\users\admin\config.php:226
src\modules\users\admin\edit.php:280
src\modules\users\admin\edit.php:281
src\modules\users\admin\edit.php:283
src\modules\users\admin\edit.php:284
src\modules\users\admin\edit.php:290
src\modules\users\admin\edit.php:291
src\modules\users\admin\edit.php:292
src\modules\users\admin\edit.php:293
src\modules\users\admin\edit.php:294
src\modules\users\admin\edit.php:295
src\modules\users\admin\editcensor.php:333
src\modules\users\admin\editcensor.php:334
src\modules\users\admin\editcensor.php:335
src\modules\users\admin\groups.php:47
src\modules\users\admin\groups.php:871
src\modules\users\admin\groups.php:897
src\modules\users\admin\groups.php:918
src\modules\users\admin\groups.php:945
src\modules\users\admin\question.php:32
src\modules\users\admin\question.php:75
src\modules\users\admin\user_add.php:69
src\modules\users\admin\user_add.php:70
src\modules\users\admin\user_add.php:71
src\modules\users\admin\user_add.php:72
src\modules\users\admin\user_add.php:75
src\modules\users\admin\user_add.php:76
src\modules\users\admin\user_add.php:77
src\modules\users\admin\user_add.php:78
src\modules\users\admin\user_add.php:79
src\modules\users\admin\user_add.php:85
src\modules\users\admin\user_waiting.php:120
src\modules\users\admin\user_waiting.php:121
src\modules\users\admin\user_waiting.php:126
src\modules\users\admin\user_waiting.php:127
src\modules\users\admin\user_waiting.php:128
src\modules\users\admin\user_waiting.php:131
src\modules\users\admin\user_waiting.php:132
src\modules\users\admin\user_waiting.php:133
src\modules\users\funcs\active.php:21
src\modules\users\funcs\editinfo.php:391
src\modules\users\funcs\editinfo.php:643
src\modules\users\funcs\editinfo.php:644
src\modules\users\funcs\editinfo.php:645
src\modules\users\funcs\editinfo.php:646
src\modules\users\funcs\editinfo.php:753
src\modules\users\funcs\editinfo.php:813
src\modules\users\funcs\editinfo.php:1065
src\modules\users\funcs\editinfo.php:1066
src\modules\users\funcs\editinfo.php:1392
src\modules\users\funcs\groups.php:586
src\modules\users\funcs\groups.php:594
src\modules\users\funcs\login.php:674
src\modules\users\funcs\login.php:747
src\modules\users\funcs\login.php:805
src\modules\users\funcs\login.php:814
src\modules\users\funcs\login.php:886
src\modules\users\funcs\lostactivelink.php:55
src\modules\users\funcs\lostactivelink.php:56
src\modules\users\funcs\lostpass.php:127
src\modules\users\funcs\lostpass.php:220
src\modules\users\funcs\lostpass.php:243
src\modules\users\funcs\register.php:129
src\modules\users\funcs\register.php:145
src\modules\users\funcs\register.php:203
src\modules\users\funcs\register.php:204
src\modules\users\funcs\register.php:205
src\modules\users\funcs\register.php:208
src\modules\users\funcs\register.php:209
src\modules\users\funcs\register.php:210
src\modules\voting\admin\content.php:39
"""

lines = files_report.strip().split('\n')
unique_files = sorted(list(set(line.split(':')[0].strip() for line in lines)))

print(f"Starting processing {len(unique_files)} unique files...")
for i, file_path in enumerate(unique_files):
    process_file(file_path)
    if (i + 1) % 10 == 0:
        print(f"Processed {i+1}/{len(unique_files)} files...")

print("Finished processing all files.")
