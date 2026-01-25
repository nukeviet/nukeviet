<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

/*
 * Note:
 * 	- Module var is: $lang, $module_file, $module_data, $module_upload, $module_theme, $module_name
 * 	- Accept global var: $db, $db_config, $global_config
 */

$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (1, 0, 'Co-operate', '', 'Co-operate', '', '', '', 0, 2, 5, 0, 'viewcat_page_new', 2, '2,3', 3, '2', '', '', 1277689708, 1277689708, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (2, 1, 'Careers at NukeViet', '', 'Careers-at-NukeViet', '', '', '', 0, 1, 6, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690086, 1277690259, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (3, 1, 'Partners', '', 'Partners', '', '', '', 0, 2, 7, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690142, 1277690291, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (4, 0, 'NukeViet news', '', 'NukeViet-news', '', '', '', 0, 1, 1, 0, 'viewcat_page_new', 3, '5,6,7', 3, '2', '', '', 1277690451, 1277690451, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (5, 4, 'Security issues', '', 'Security-issues', '', '', '', 0, 1, 2, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690497, 1277690564, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (6, 4, 'Release notes', '', 'Release-notes', '', '', '', 0, 2, 3, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690588, 1277690588, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (7, 4, 'Development team talk', '', 'Development-team-talk', '', '', '', 0, 3, 4, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690652, 1277690652, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (8, 0, 'NukeViet community', '', 'NukeViet-community', '', '', '', 0, 3, 8, 0, 'viewcat_page_new', 3, '9,10,11', 3, '2', '', '', 1277690748, 1277690748, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (9, 8, 'Activities', '', 'Activities', '', '', '', 0, 1, 9, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690765, 1277690765, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (10, 8, 'Events', '', 'Events', '', '', '', 0, 2, 10, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690783, 1277690783, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (11, 8, 'Faces of week &#x3A;D', '', 'Faces-of-week-D', '', '', '', 0, 3, 11, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690821, 1277690821, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (12, 0, 'Lastest technologies', '', 'Lastest-technologies', '', '', '', 0, 4, 12, 0, 'viewcat_page_new', 2, '13,14', 3, '2', '', '', 1277690888, 1277690888, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (13, 12, 'World wide web', '', 'World-wide-web', '', '', '', 0, 1, 13, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690934, 1277690934, '6', 1) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_cat (catid, parentid, title, titlesite, alias, description, descriptionhtml, image, viewdescription, weight, sort, lev, viewcat, numsubcat, subcatid, numlinks, newday, keywords, admins, add_time, edit_time, groups_view, status) VALUES  (14, 12, 'Around internet', '', 'Around-internet', '', '', '', 0, 2, 14, 1, 'viewcat_page_new', 0, '', 3, '2', '', '', 1277690982, 1277690982, '6', 1) ");

$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES (1, 1, '1,7,8', 0, 8, 'VINADES', 0, 1277689959, 1277690410, 1, 1277689920, 0, 2, 'Invite to co-operate announcement', 'Invite-to-co-operate-announcement', 'VINADES.,JSC was founded in order to professionalize NukeViet opensource development and release. We also using NukeViet in our bussiness projects to make it continue developing. Include Advertisment, provide hosting services for NukeViet CMS development.', 'hoptac.jpg', '', 1, 1, '6', 1, 2, 0, 0, 0) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES (2, 14, '14,8', 0, 8, '', 1, 1277691366, 1277691470, 1, 1277691360, 0, 2, 'What does WWW mean?', 'What-does-WWW-mean', 'The World Wide Web, abbreviated as WWW and commonly known as the Web, is a system of interlinked hypertext&nbsp; documents accessed via the Internet.', '', '', 0, 1, 2, 1, 0, 0, 0, 0) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES (3, 12, '12,7', 0, 8, '', 2, 1277691851, 1287160943, 1, 1277691840, 0, 2, 'HTML 5 review', 'HTML-5-review', 'I have to say that my money used to be on XHTML 2.0 eventually winning the battle for the next great web standard. Either that, or the two titans would continue to battle it out for the forseable future, leading to an increasingly fragmented web.', '', '',0, 1, '6', 1, 2, 0, 0, 0) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES (4, 4, '4', 0, 1, 'VOVNews&#x002F;VNA', 0, 1292959020, 1292959513, 1, 1292959020, 0, 2, 'First open-source company starts operation', 'First-open-source-company-starts-operation', 'The Vietnam Open Source Development Joint Stock Company (VINADES.,JSC), the first firm operating in the field of open source in the country, made its debut on February 25.', 'nangly.jpg', '', 1, 1, '6', 1, 1, 0, 0, 0) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_rows (id, catid, listcatid, topicid, admin_id, author, sourceid, addtime, edittime, status, publtime, exptime, archive, title, alias, hometext, homeimgfile, homeimgalt, homeimgthumb, inhome, allowed_comm, allowed_rating, hitstotal, hitscm, total_rating, click_rating) VALUES (5, 4, '4', 0, 1, '', 0, 1292959490, 1292959664, 1, 1292959440, 0, 2, 'NukeViet 3.0 - New CMS for News site', 'NukeViet-30-New-CMS-for-News-site', 'NukeViet 3.0 is a professional system: VINADES.,JSC founded to maintain and improve NukeViet 3.0 features. VINADES.,JSC co-operated with many professional hosting providers to test compatibility issues.', 'nukeviet-cms.jpg', '', 1, 1, '6', 1, 1, 0, 0, 0) ");

$bodyhtml = '<p> <span style="color: black;"><span style="color: black;"><font size="2"><span style="font-family: verdana,sans-serif;">VIETNAM OPEN SOURCE DEVELOPMENT COMPANY (VINADES.,JSC)<br /> Head office: 6th floor, Song Da building, No. 131 Tran Phu Street, Ha Dong Ward, Hanoi City, Vietnam.<br /> Mobile: (+84) 24 8587 2007<br /> Fax: (+84) 24 3550 0914<br /> Website: <a f8f55ee40942436149="true" href="http://www.vinades.vn/" target="_blank">www.vinades.vn</a> - <a f8f55ee40942436149="true" href="http://www.nukeviet.vn/" target="_blank">www.nukeviet.vn</a></span></font></span></span></p><div h4f82558737983="nukeviet.vn" style="display: inline; cursor: pointer; padding-right: 16px; width: 16px; height: 16px;"> <span style="color: black;"><span style="color: black;"><font size="2"><span style="font-family: verdana,sans-serif;">&nbsp;</span></font></span></span></div><br /><p> <span style="color: black;"><span style="color: black;"><font size="2"><span style="font-family: verdana,sans-serif;">Email: <a href="mailto:contact@vinades.vn" target="_blank">contact@vinades.vn</a><br /> <br /> <br /> Dear valued customers and partners,<br /> <br /> VINADES.,JSC was founded in order to professionalize NukeViet opensource development and release. We also using NukeViet in our bussiness projects to make it continue developing.<br /> <br /> NukeViet is a Content Management System (CMS). 1st general purpose CMS developed by Vietnamese community. It have so many pros. Ex: Biggest community in VietNam, pure Vietnamese, easy to use, easy to develop...<br /> <br /> NukeViet 3 is lastest version of NukeViet and it still developing but almost complete with many advantage features.<br /> <br /> With respects to invite hosting - domain providers, and all company that pay attension to NukeViet in bussiness co-operate.<br /> <br /> Co-operate types:<br /> <br /> 1. Website advertisement, banners exchange, links:<br /> a. Description:<br /> Website advertising &amp; communication channels.<br /> On each release version of NukeViet.<br /> b. Benefits:<br /> Broadcast to all end users on both side.<br /> Reduce advertisement cost.<br /> c. Warranties:<br /> Place advertisement banner of partners on both side.<br /> Open sub-forum at NukeViet.VN to support end users who using hosting services providing by partners.<br /> <br /> 2. Provide host packet for NukeViet development testing purpose:<br /> <br /> a. Description:<br /> Sign the contract and agreements.<br /> Partners provide all types of hosting packet for VINADES.,JSC. Each type at least 1 re-sale packet.<br /> VINADES.,JSC provide an certificate verify host providing by partner compartable with NukeViet.<br /> b. Benefits:<br /> Expand market.<br /> Reduce cost, improve bussiness value.<br /> c. Warranties:<br /> Partner provide free hosting packet for VINADES.,JSC to test NukeViet compatibility.<br /> VINADES.JSC annoucement tested result to community.<br /> <br /> 3. Support end users:<br /> a. Description:<br /> Co-operate to solve problem of end user.<br /> Partners send end user requires about NukeViet CMS to VINADES.,JSC. VINADES also send user requires about hosting services to partners.<br /> b. Benefits:<br /> Reduce cost, human resources to support end users.<br /> Support end user more effective.<br /> c. Warranties:<br /> Solve end user requires as soon as possible.<br /> <br /> 4. Other types:<br /> Besides, as a publisher of NukeViet CMS, we also place advertisements on software user interface, sample articles in each release version. With thousands of downloaded hits each release version, we believe that it is the most effective advertisement type to webmasters.<br /> If partners have any ideas about new co-operate types. You are welcome and feel free to send specifics to us. Our slogan is &quot;Co-operate for development&quot;.<br /> <br /> We look forward to co-operating with you.<br /> <br /> Sincerely,<br /> <br /> VINADES.,JSC</span></font></span></span></p>';
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save) VALUES (1, '', '', :bodyhtml, '', 2, 0, 1, 1, 1) ");
$sth->bindParam(':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen($bodyhtml));
$sth->execute();

$bodyhtml = '<p> With a web browser, one can view web pages&nbsp; that may contain text, images, videos, and other multimedia&nbsp; and navigate between them by using hyperlinks. Using concepts from earlier hypertext systems, British engineer and computer scientist Sir Tim Berners-Lee, now the Director of the World Wide Web Consortium, wrote a proposal in March 1989 for what would eventually become the World Wide Web. He was later joined by Belgian computer scientist Robert Cailliau while both were working at CERN in Geneva, Switzerland. In 1990, they proposed using &quot;HyperText to link and access information of various kinds as a web of nodes in which the user can browse at will&quot;, and released that web in December.<br /> <br /> &quot;The World-Wide Web (W3) was developed to be a pool of human knowledge, which would allow collaborators in remote sites to share their ideas and all aspects of a common project.&quot;. If two projects are independently crea-ted, rather than have a central figure make the changes, the two bodies of information could form into one cohesive piece of work.</p><p> For more detail. See <a href="http://en.wikipedia.org/wiki/World_Wide_Web" target="_blank">Wikipedia</a></p>';
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save) VALUES (2, '', '', :bodyhtml,'', 1, 0, 1, 1, 1)");
$sth->bindParam(':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen($bodyhtml));
$sth->execute();

$bodyhtml = "<p> But now that the W3C has admitted defeat, and abandoned <span class=\"caps\">XHTML</span> 2.0, there’s now no getting away f-rom the fact that <span class=\"caps\">HTML</span> 5 is the future. As such, I’ve now spent some time taking a look at this emerging standard, and hope you’ll endulge my ego by taking a glance over my thoughts on the matter.</p><p> Before I get started though, I have to say that I’m very impressed by what I’ve seen. It’s a good set of standards that are being cre-ated, and I hope that they will gradually be adopted over the next few years.</p><h2> New markup</h2><p> <span class=\"caps\">HTML</span> 5 introduces some new markup elements to encourage better structure within documents. The most important of these is &lt;section&gt;, which is used to define a hierarchy within a document. Sections can be nested to define subsections, and each section can be broken up into &lt;header&gt; and &lt;footer&gt; areas.</p><p> The important thing about this addition is that it removes the previous dependancy on &lt;h1&gt;, &lt;h2&gt; and related tags to define structure. Within each &lt;section&gt;, the top level heading is always &lt;h1&gt;. You can use as many &lt;h1&gt; tags as you like within your content, so long as they are correctly nested within &lt;section&gt; tags.</p><p> There’s a plethora of other new tags, all of which seem pretty useful. The best thing about all of this, however, is that there’s no reason not to start using them right away. There’s a small piece of JavaScript that’s needed to make Internet Explorer behave, but aside f-rom that it’s all good. More details about this hack are available at <a href=\"http://www.diveintohtml5.org/\">http://www.diveintohtml5.org</a></p><h2> Easier media embedding</h2><p> <span class=\"caps\">HTML</span> 5 defines some new tags that will make it a lot easier to embed video and audio into pages. In the same way that images are embedded using &lt;img&gt; tags, so now can video and audio files be embedded using &lt;video&gt; and &lt;audio&gt;.</p><p> I don’t think than anyone is going to complain about these new features. They free us f-rom relying on third-party plugins, such as Adobe Flash, for such simple activities such as playing video.</p><p> Unfortunately, due to some annoying licensing conditions and a lack of support for the open-source Theora codec, actually using these tags at the moment requires that videos are encoded in two different formats. Even then, you’ll still need to still provide an Adobe Flash fallback for Internet Explorer.</p><p> You’ll need to be pretty devoted to <span class=\"caps\">HTML</span> 5 to use these tags yet…</p><h2> Relaxed markup rules</h2><p> This is one thorny subject. You know how we’ve all been so good recently with our well-formed <span class=\"caps\">XHTML</span>, quoting those attributes and closing those tags? Now there’s no need to, apparently…</p><p> On the surface, this seems like a big step backwards into the bad days of tag soup. However, if you dig deeper, the reasoning behind this decision goes something like this:</p><ol> <li> It’s unnacceptable to crash out an entire <span class=\"caps\">HTML</span> page just because of a simple <span class=\"caps\">XML</span> syntax error.</li> <li> This means that browsers cannot use an <span class=\"caps\">XML</span> parser, and must instead use a HTML-aware fault-tolerant parser.</li> <li> For consistency, all browsers should handle any such “syntax errors” (such as unquoted attributes and unclosed tags), in the same way.</li> <li> If all browsers are behaving in the same way, then unquoted attributes and unclosed tags are not really syntax errors any more. In fact, by leaving them out of our pages, we can save a few bytes!</li></ol><p> This isn’t to say that you have to throw away those <span class=\"caps\">XHTML</span> coding habits. It’s still all valid <span class=\"caps\">HTML</span> 5. In fact, if you really want to be strict, you can set a different content-type header to enforce well-formed <span class=\"caps\">XHTML</span>. But for most people, we’ll just carry on coding well-formed <span class=\"caps\">HTML</span> with the odd typo, but no longer have to worry about clients screaming at us when the perfectly-rendered page doesn’t validate.</p><h2> So what now?</h2><p> The <span class=\"caps\">HTML</span> 5 specification is getting pretty close to stable, so it’s now safe to use bits of this new standard in your code. How much you use is entirely a personal choice. However, we should all get used to the new markup over the next few years, because <span class=\"caps\">HTML</span> 5 is assuredly here to stay.</p><p> Myself, I’ll be switching to the new doctype and using the new markup for document sections in my code. This step involves very little effort and does a good job of showing support for the new specification.</p><p> The new media tags are another matter. Until all platforms support a single video format, it’s simply not sustainable to be transcoding all videos into two filetypes. When this is coupled with having to provide a Flash fallback, it all seems like a pretty poor return on investment.</p><p> These features will no doubt become more useable over the next few years, as newer browser take the place of old. One day, hopefully, we’ll be able write clean, semantic pages without having to worry about backwards-compatibility.</p><p> Part of this progress relies on web developers using these new standards in our pages. By adopting new technology, we show our support for the standards it represents and place pressure on browser vendors to adhere to those standards. It’s a bit of effort in the short term, but in the long term it will pay dividends.</p>', 'http://www.etianen.com/blog/developers/2010/2/html-5-review/";
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save) VALUES (3, '', '', :bodyhtml, '', 2, 0, 1, 1, 1)");
$sth->bindParam(':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen($bodyhtml));
$sth->execute();

$bodyhtml = '<p> <span>The Hanoi-based company will further develop and popularise an open source content management system best known as NukeViet in the country. </span></p><p> <span>VINADES Chairman Nguyen Anh Tu said NukeViet is totally free and users can download the product at www.nukeviet.vn. </span></p><p> <span>NukeViet has been widely used across the country over the past five years. The system, built on PHP-Nuke and MySQL database, enables users to easily post and manage files on the Internet or Intranet.</span></p>';
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save) VALUES (4, '', '', :bodyhtml, '', 0, 0, 1, 1, 1)");
$sth->bindParam(':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen($bodyhtml));
$sth->execute();

$bodyhtml = '<p> NukeViet also testing by many experienced webmasters to optimize system features. NukeViet&#039;s core team are programming enthusiasts. All of them want to make NukeViet become the best and most popular open source CMS.</p><p> <strong>NukeViet 3.0 is a powerful system:</strong><br /> Learn by experiences f-rom NukeViet 2.0, NukeViet 3.0 build ground up on latest web technologies, allow you easily cre-ate portal, online news express, social network, e commerce system.<br /> NukeViet 3.0 can process huge amount of data. It was used by many companies, corporation&#039;s website with millions of news entries with high traffic.<br /> <br /> <strong>NukeViet 3.0 is easy to use system:</strong><br /> NukeViet allow you easily to customize and instantly use without any line of code. As developers, NukeViet help you build your own modules rapidly.</p><h2> NukeViet 3.0 features:</h2><p> <strong>Technology bases:</strong><br /> NukeViet 3.0 using PHP 5 and MySQL 5 as main programming languages. XTemplate and jQuery for use Ajax f-rom system core.<br /> NukeViet 3.0 is fully validated with xHTML 1.0, CSS 2.1 and compatible with all major browsers.<br /> NukeViet 3.0 layout website using grid CSS framework like BluePrintCSS for design templates rapidly.<br /> <br /> NukeViet 3.0 has it own core libraries and it is platform independent. You can build your own modules with basic knowledge of PHP and MySQL.<br /> <br /> <strong>Module structure:</strong><br /> NukeViet 3.0 re-construct module structure. All module files packed into a particular folder. It&#039;s also define module block and module theme for layout modules in many ways.<br /> <br /> NukeViet 3.0 support modules can be multiply. We called it abstract modules. It help users automatic cre-ate many modules without any line of code f-rom any exists module which support cre-ate abstract modules.<br /> <br /> NukeViet 3.0 support automatic setup modules, blocks, themes f-rom Admin Control Panel. It&#039;s also allow you to share your modules by packed it into packets. NukeViet allow grant, deny access or even re-install, de-lete module.<br /> <br /> <strong>Multi language:</strong><br /> NukeViet 3 support multi languages in 2 types. Multi interface languages and multi database languages. It had features support administrators to build new languages. In NukeViet 3, admin language, user language, interface language, database language are separate for easily build multi languages systems.<br /> <br /> <strong>Right:</strong><br /> All manage features only access in admin area. NukeViet 3.0 allow grant access by module and language. It also allow cre-ate user groups and grant access modules by group.<br /> <br /> <strong>Themes:</strong><br /> NukeViet 3.0 support automatic install and uninstall themes. You can easily customize themes in module and module&#039;s functions. NukeViet store HTML, CSS code separately f-rom PHP code to help designers rapidly layout website.<br /> <br /> <strong>Customize website using blocks</strong><br /> A block can be a widget, advertisement pictures or any defined data. You can place block in many positions visually by drag and d-rop or argument it in Admin Control Panel.<br /> <br /> <strong>Securities:</strong><br /> NukeViet using security filters to filter data upload.<br /> Logging and control access f-rom many search engine as Google, Yahoo or any search engine.<br /> Anti spam using Captcha, anti flood data...<br /> NukeViet 3.0 has logging systems to log and track information about client to prevent attack.<br /> NukeViet 3.0 support automatic up-date to fix security issues or upgrade your website to latest version of NukeViet.<br /> <br /> <strong>Database:</strong><br /> You can backup database and download backup files to restore database to any point you restored your database.<br /> <br /> <strong>Control errors report</strong><br /> You can configure to display each type of error only one time. System then sent log files about this error to administrator via email.<br /> <br /> <strong>SEO:</strong><br /> Support SEO link<br /> Manage and customize website title<br /> Manage meta tag<br /> <br /> Support keywords for cre-ate statistic via search engine<br /> <br /> <strong>Prepared for integrate with third party application</strong><br /> NukeViet 3.0 has it own user database and many built-in methods to connect with many forum application. PHPBB or VBB can integrate and use with NukeViet 3.0 by single click.<br /> <br /> <strong>Distributed login</strong><br /> NukeViet support login by OpenID. Users can login to your website by accounts f-rom popular and well-known provider, such as Google, Yahoo or other OpenID providers. It help your website more accessible and reduce user&#039;s time to filling out registration forms.<br /> <br /> Download NukeViet 3.0: <a href="http://code.google.com/p/nuke-viet/downloads/list">http://code.google.com/p/nuke-viet/downloads/list</a><br /> Website: <a href="https://nukeviet.vn/">https://nukeviet.vn</a></p>';
$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_detail (id, titlesite, description, bodyhtml, sourcetext, imgposition, copyright, allowed_send, allowed_print, allowed_save) VALUES (5, '', '', :bodyhtml,'', 2, 0, 1, 1, 1)");
$sth->bindParam(':bodyhtml', $bodyhtml, PDO::PARAM_STR, strlen($bodyhtml));
$sth->execute();

$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (1, 0, 4,'Hot News', 'Hot-News', '', '', 1, '', 1279963759, 1279963759)");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_block_cat (bid, adddefault, numbers, title, alias, image, description, weight, keywords, add_time, edit_time) VALUES (2, 1, 4, 'Top News', 'Top-News', '', '', 2, '', 1279963766, 1279963766)");

$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_sources VALUES (1, 'Wikipedia', 'http://www.wikipedia.org', '', 1, 1277691366, 1277691366) ");
$db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . "_sources VALUES (2, 'Enlightened Website Development', 'http://www.etianen.com', '', 2, 1277691851, 1277691851)");

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_tags (tid, numnews, title, alias, image, description, keywords) VALUES (?, ?, ?, ?, ?, ?, ?)');
$sth->execute([
    1,
    0,
    'VINADES',
    'vinades',
    '',
    '',
    'vinades'
]);
$sth->execute([
    2,
    0,
    'Web',
    'web',
    '',
    '',
    'Web'
]);
$sth->execute([
    3,
    0,
    'HTML5',
    'html5',
    '',
    '',
    'html5'
]);
$sth->execute([
    4,
    0,
    'Nguyen Anh Tu',
    'nguyen-anh-tu',
    '',
    '',
    'nguyen anh tu'
]);
$sth->execute([
    5,
    0,
    'NukeViet',
    'nukeviet',
    '',
    '',
    'nukeviet'
]);

$sth = $db->prepare('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_tags_id (id, tid, keyword) VALUES (?, ?, ?)');
$sth->execute([
    1,
    1,
    'vinades'
]);
$sth->execute([
    2,
    2,
    'web'
]);
$sth->execute([
    3,
    3,
    'html5'
]);
$sth->execute([
    4,
    1,
    'vinades'
]);
$sth->execute([
    4,
    4,
    'nguyen anh tu'
]);
$sth->execute([
    5,
    5,
    'nukeviet'
]);
$sth->execute([
    5,
    1,
    'vinades'
]);

$copyright = 'Note: The above article reprinted at the website or other media sources not specify the source https://nukeviet.vn is copyright infringement';
$db->query('UPDATE ' . $db_config['prefix'] . '_config SET config_value = ' . $db->quote($copyright) . ' WHERE module = ' . $db->quote($module_name) . " AND config_name = 'copyright' AND lang=" . $db->quote($lang));

$result = $db->query('SELECT catid FROM ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_cat ORDER BY sort ASC');
while (list($catid_i) = $result->fetch(3)) {
    $db->exec('CREATE TABLE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_' . $catid_i . ' LIKE ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows');
}

$result = $db->query('SELECT id, listcatid FROM ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows ORDER BY id ASC');

while (list($id, $listcatid) = $result->fetch(3)) {
    $arr_catid = explode(',', $listcatid);
    foreach ($arr_catid as $catid) {
        $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_' . $catid . ' SELECT * FROM ' . $db_config['prefix'] . '_' . $lang . '_' . $module_data . '_rows WHERE id=' . $id);
    }
}
$result->closeCursor();
