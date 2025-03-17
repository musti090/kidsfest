<table style="width:100%">
    <tr>
        <td style="width:30%"><h3>Ad,Soyad:</h3></td>
        <td style="width:70%">{{ $appeals->fullname }}</td>
    </tr>
    <tr>
        <td><h3>Email:</h3></td>
        <td>{{ $appeals->email }}</td>
    </tr>
    <tr>
        <td><h3>Müraciətin növü:</h3></td>
        <td>{{ $appeals->appealsType->name }}</td>
    </tr>
    <tr>
        <td style="vertical-align: top"><h3>Müraciətin mətni:</h3></td>
        <td>{{ $appeals->text }}</td>
    </tr>
</table>





