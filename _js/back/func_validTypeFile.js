/* BACK
   ������� �������� �� ������� ������� ����� ������, ����������� � <input type="file" ... />
*/
function fileIsImage(field) {
  if ( !/.*\.jpg$|.*\.png$|.*\.gif$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('�� ���������� ��� �����!\n (����������� ���� png,jpg,gif)');
  }
}

function fileIsPrice(field) {
  if ( !/.*\.doc$|.*\.xls$|.*\.rar$|.*\.zip|.*\.7z$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('�� ���������� ��� �����!\n (����������� ���� doc,xls,rar,zip,7z)');
  }
}

function fileIsArchive(field) {
  if ( !/.*\.zip$|.*\.rar|.*\.7z$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('�� ���������� ��� �����!\n (����������� ���� rar,zip,7z)');
  }
}

function fileIsDoc(field) {
  if ( !/.*\.zip$|.*\.doc$|.*\.pdf$|.*\.rar$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('�� ���������� ��� �����!\n (����������� ���� rar,zip,doc,pdf)');
  }
}
