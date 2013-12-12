<table class="list" id="records_list">
	<thead>
		<tr>
			<th>Id</th>
			<th>Domainname</th>
			<th class="{literal}{sorter: 'natural'}{/literal}">Recordname</th>
			<th>{t}Domaintype{/t}</th>
			<th>{t}Recordtype{/t}</th>
			<th>{t}Content{/t}</th>
			<th>TTL</th>
			<th>{t}Priority{/t}</th>
			<th>{t}Last change{/t}</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$records item="record"}
		<tr style="background-color: #{cycle values="eee,c8c8c8"};" onclick="window.location.href = '?p=domainedit&pp[domain_id]={$record.domain_id}&pin={$record.id}';">
			<td>{$record.id}</td>
			<td>{$record.domain_name}</td>
			<td>{$record.name}</td>
			<td>{$record.domain_type}</td>
			<td>{$record.type}</td>
			<td><div style="overflow: hidden; max-width: 500px;">{$record.content}</div></td>
			<td>{$record.ttl}</td>
			<td>{$record.prio}</td>
			<td>{$record.change_date|date_format:"%c"}</td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="9">{t}Nothing found{/t}...</td>
		</tr>
		{/foreach}
	</tbody>
</table>
