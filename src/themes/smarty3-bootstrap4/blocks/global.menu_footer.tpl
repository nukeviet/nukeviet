<div class="ms-footbar-block">
                <h3 class="ms-footbar-title">{$title}</h3>
                <ul class="list-unstyled ms-icon-list three_cols">
                {foreach $row as $rows}
                  <li>
                    <a href="{$rows.link}">
                      <i class="{$rows.css}"></i> {$rows.title}</a>
                  </li>
                  {/foreach}
                </ul>
              </div>
             