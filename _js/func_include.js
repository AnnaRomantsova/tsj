/* ALL
   ���������� ��� ����� � src �� ������� includeFiles
*/
function include(includeFiles) {
	for(var i=0;i<includeFiles.length;i++) 
		document.write('<script type="text/javascript" src="'+includeFiles[i]+'"></script>');
}