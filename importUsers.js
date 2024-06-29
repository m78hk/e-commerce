const admin = require('firebase-admin');
const serviceAccount = require('/Applications/XAMPP/xamppfiles/htdocs/www/abcshop-web-firebase-adminsdk-g6owf-4eaa080eb8.json');

admin.initializeApp({
  credential: admin.credential.cert(serviceAccount)
});

// 示例用户数据（根据你的数据库数据调整）
const users = [
  {          
    uid: '4',  
    email: 'abc@abc.com',
    passwordHash: Buffer.from('$2y$10$W7wOVvJjYkgdGll5C.gIw.Toq6qnZbrm/7rViCgRfKZzZ1QJUzvqu'),
    displayName: 'roy',
  },
  {
    uid: '12',
    email: 'qwe@qwe.com',
    passwordHash: Buffer.from('$2y$10$TU1h.mSvVZ3oPTE4.KV5L.HYV38SYvvNvGYXCTlq8h2kXUHaWE/Ri'),
    displayName: 'customer1',
  },
  {
    uid: '14',
    email: 'm78.roy.mo@gmail.com',
    passwordHash: Buffer.from('$2y$10$nBmOodFRSaqic6yGnWyAOeuKCcESyq8qhJeMtiwvDlutqwAVVcSKi'),
    displayName: 'customer 3',
  },
];

admin.auth().importUsers(users, {
  hash: {
    algorithm: 'BCRYPT',
  },
})
  .then((results) => {
    console.log('Successfully imported users:', results.successCount);
    console.log('Errors encountered:', results.failureCount);
    if (results.errors.length > 0) {
      console.log('Error details:', results.errors);
    }
  })
  .catch((error) => {
    console.error('Error importing users:', error);
  });