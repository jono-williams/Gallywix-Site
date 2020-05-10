const Discord = require('discord.js');
const fs = require('fs');
const readline = require('readline');
const {google} = require('googleapis');
var mysql      = require('mysql');
var util = require('util');
var log_file = fs.createWriteStream(__dirname + '/debug.log', {flags : 'w'});
var log_stdout = process.stdout;

console.log = function(d) { //
  log_file.write(util.format(d) + '\n');
  log_stdout.write(util.format(d) + '\n');
};

var connection = mysql.createConnection({
  host     : 'localhost',
  user     : 'root',
  password : 'bogipogi',
  database : 'cias'
});

gbanks = {
  'NA': {},
  'EU': {}
}

async function fetchGBanks() {
  const fileStream = fs.createReadStream('gbanks.txt');

  const rl = readline.createInterface({
    input: fileStream,
    crlfDelay: Infinity
  });

  for await (const line of rl) {
    split = line.split(' ')
    gbanks['EU'][split[0].toLowerCase()] = {'Horde': '', 'Alliance': ''};
    gbanks['EU'][split[0].toLowerCase()]['Horde'] = split[2];
    gbanks['EU'][split[0].toLowerCase()]['Alliance'] = split[1];
  }
}

fetchGBanks();

function messageAccepted(msg, runArray) {
  if(gbanks['EU'][runArray[runArray.length-5].toLowerCase()]) {
    if(runArray[3].includes('Alliance')) {
      faction = "Alliance";
    } else {
      faction = "Horde";
    }

    if(runArray[2] == "0") {
      if(runArray[3].includes("Alliance-TC")) {
        goldToSend = parseInt(runArray[1]) - (parseInt(runArray[1]) * 0.15);
      } else {
        goldToSend = parseInt(runArray[1]) - (parseInt(runArray[1]) * 0.10);
      }
    } else {
      goldToSend = parseInt(runArray[1]);
    }

    msgSend = "\n**Hello!** You just posted a boost [" + runArray[runArray.length-4] + "]\n\n";
    msgSend += "**Please send the collected gold using this addon string:**\n";
    msgSend += "**IF GOLD WAS ON HORDE:**\n";
    msgSend += "```\n";
    msgSend += gbanks['EU'][runArray[runArray.length-5].replace(' ', '').toLowerCase()]['Horde'] + ":" + (parseInt(goldToSend) - 1) + ":" + runArray[runArray.length-4] + ":" + runArray.slice(0, -3).join(' ');
    msgSend += "```\n";
    msgSend += "**IF GOLD WAS ON ALLIANCE:**\n";
    msgSend += "```\n";
    msgSend += gbanks['EU'][runArray[runArray.length-5].replace(' ', '').toLowerCase()]['Alliance'] + ":" + (parseInt(goldToSend) - 1) + ":" + runArray[runArray.length-4] + ":" + runArray.slice(0, -3).join(' ');
    msgSend += "```\n";

    msg.author.send(msgSend);
  }
}

connection.connect(function(err) {
    if (err) throw err
});

var botId = 642022527849201677;
var mplusId = 506976442358169611;
var levellingId = 519126073255133194;
var pvpId = 533002875488698408;
var levellingAndPvPLength = 9;
var mPlusLength = 11;
var euSheetId = "1VVaVCg7zsFMWOHxwRd5AuqE-a3pHKvbaOLUgysw9LZk";

const key = require('./service-account-credentials.json');

const drive = google.drive('v3');
const googleClient = new google.auth.JWT(
  key.client_email,
  null,
  key.private_key,
  ['https://www.googleapis.com/auth/spreadsheets'],
  null
);

googleClient.authorize((err, tokens) => {
        if (err) {
            reject(err);
        } else {
            google.options({
                auth: googleClient
            });
        }
});

const client = new Discord.Client();

client.on('ready', () => {
  console.log(`Logged in as ${client.user.tag}!`);
});

client.on('message', msg => {
  if(msg.author.id != botId) {
    if(msg.channel.id == mplusId) {
      console.log('on.message.mplus');
      console.log(msg);
      mplusAdd(msg);
    } else if(msg.channel.id == levellingId) {
      console.log('on.message.levelling');
      console.log(msg);
      levellingAdd(msg);
    } else if(msg.channel.id == pvpId) {
      console.log('on.message.pvp');
      console.log(msg);
      pvpAdd(msg);
    }
  }
});

client.on('messageDelete', msg => {
  if(msg.author.id != botId) {
    if(msg.channel.id == mplusId) {
      console.log('on.messageDelete.mplus');
      console.log(msg);
      if(msg.reactions.cache.get('✅')) {
        mplusDelete(msg);
        balanceRemove(msg);
      }
    } else if(msg.channel.id == levellingId) {
      console.log('on.messageDelete.levelling');
      console.log(msg);
      if(msg.reactions.cache.get('✅')) {
        levellingDelete(msg);
        balanceRemove(msg);
      }
    } else if(msg.channel.id == pvpId) {
      console.log('on.messageDelete.pvp');
      console.log(msg);
      if(msg.reactions.cache.get('✅')) {
        pvpDelete(msg);
        balanceRemove(msg);
      }
    }
  }
});

client.on('messageUpdate', function(oldmsg, newmsg) {
  if(oldmsg.author.id != botId) {
    if(oldmsg.channel.id == mplusId) {
      newmsg.reactions.removeAll();
      console.log('on.messageUpdate.mplus');
      console.log(oldmsg);
      console.log(newmsg);
      if(oldmsg.reactions.cache.get('✅')) {
        mplusUpdate(oldmsg, newmsg);
      } else if(oldmsg.reactions.cache.get('❎')) {
        mplusAdd(newmsg);
      }
    } else if(oldmsg.channel.id == levellingId) {
      console.log('on.messageUpdate.levelling');
      console.log(oldmsg);
      console.log(newmsg);
      newmsg.reactions.removeAll();
      if(oldmsg.reactions.cache.get('✅')) {
        levellingUpdate(oldmsg, newmsg);
      } else if(oldmsg.reactions.cache.get('❎')) {
        levellingAdd(newmsg);
      }
    } else if(oldmsg.channel.id == pvpId) {
      console.log('on.messageUpdate.pvp');
      console.log(oldmsg);
      console.log(newmsg);
      newmsg.reactions.removeAll();
      if(oldmsg.reactions.cache.get('✅')) {
        pvpUpdate(oldmsg, newmsg);
      } else if(oldmsg.reactions.cache.get('❎')) {
        pvpAdd(newmsg);
      }
    }
  }
});

client.login('NjQyMDIyNTI3ODQ5MjAxNjc3.XmfFKQ.YGCjqArJkTJFfrKcjuLrklUO9VM');

function mplusUpdate(oldmsg, newmsg) {
  mplusDelete(oldmsg);
  balanceRemove(oldmsg);
  setTimeout(function() {
    mplusAdd(newmsg);
  }, 5000);
}

function levellingUpdate(oldmsg, newmsg) {
  levellingDelete(oldmsg);
  balanceRemove(oldmsg);
  setTimeout(function() {
    levellingAdd(newmsg);
  }, 5000);
}

function pvpUpdate(oldmsg, newmsg) {
  pvpDelete(oldmsg);
  balanceRemove(oldmsg);
  setTimeout(function() {
    pvpAdd(newmsg);
  }, 5000);
}

function mplusDelete(msg) {
  fullString = msg.content;
  arrayString = fullString.split('\n');
  runId = arrayString[mPlusLength-1];
  let spreadsheetId = euSheetId;
  let sheetName = 'M+!B:L';
  let sheets = google.sheets('v4');
  sheets.spreadsheets.values.get({
     auth: googleClient,
     spreadsheetId: spreadsheetId,
     range: sheetName,
  }, function (err, response) {
     if (!err) {
      currentSheet = response.data.values;
      for (var i = 0, len = currentSheet.length; i < len; i++) {
        if (currentSheet[i][10] === runId) {
          break;
        }
      }
      rowToClear = i + 1;
      placeToDelete = 'M+!B' + rowToClear + ':L' + rowToClear;
      sheets.spreadsheets.values.clear({
        auth: googleClient,
        spreadsheetId: spreadsheetId,
        range: placeToDelete,
      });
      console.log(response);
      dbDelete('mythic_plus',runId);
    } else {
      console.log(err);
    }
  });
}

function levellingDelete(msg) {
  fullString = msg.content;
  arrayString = fullString.split('\n');
  runId = arrayString[levellingAndPvPLength-1];
  let spreadsheetId = euSheetId;
  let sheetName = 'Leveling!B:J';
  let sheets = google.sheets('v4');
  sheets.spreadsheets.values.get({
     auth: googleClient,
     spreadsheetId: spreadsheetId,
     range: sheetName,
  }, function (err, response) {
     if (!err) {
      currentSheet = response.data.values;
      for (var i = 0, len = currentSheet.length; i < len; i++) {
        if (currentSheet[i][8] === runId) {
          break;
        }
      }
      rowToClear = i + 1;
      placeToDelete = 'Leveling!B' + rowToClear + ':J' + rowToClear;
      sheets.spreadsheets.values.clear({
        auth: googleClient,
        spreadsheetId: spreadsheetId,
        range: placeToDelete,
      });
      console.log(response);
      dbDelete('levelling', runId);
    } else {
      console.log(err);
    }
  });
}

function pvpDelete(msg) {
  fullString = msg.content;
  arrayString = fullString.split('\n');
  runId = arrayString[levellingAndPvPLength-1];
  let spreadsheetId = euSheetId;
  let sheetName = 'PvP!B:J';
  let sheets = google.sheets('v4');
  sheets.spreadsheets.values.get({
     auth: googleClient,
     spreadsheetId: spreadsheetId,
     range: sheetName,
  }, function (err, response) {
     if (!err) {
      currentSheet = response.data.values;
      for (var i = 0, len = currentSheet.length; i < len; i++) {
        if (currentSheet[i][8] === runId) {
          break;
        }
      }
      rowToClear = i + 1;
      placeToDelete = 'PvP!B' + rowToClear + ':J' + rowToClear;
      sheets.spreadsheets.values.clear({
        auth: googleClient,
        spreadsheetId: spreadsheetId,
        range: placeToDelete,
      });
      console.log(response);
      dbDelete('pvp',runId);
    } else {
      console.log(err);
    }
  });
}

function levellingAdd(msg) {
  fullString = msg.content;
  var arrayString = fullString.split('\n');
  count = arrayString.length;
  runId = arrayString[levellingAndPvPLength-1];
  var sql = "SELECT * FROM levelling WHERE id_from_sheet = '"+ runId + "'";
  connection.query(sql, function (err, result) {
    if (err) throw err;
    console.log("Number of records: " + result.length + " for id: " + runId);
    if(msg.attachments.length != 1) {
      if(count != 1 && !checkURL(fullString)) {
        if(result.length == 0) {
          if(count == levellingAndPvPLength && arrayString[4].includes("-") && arrayString[6].includes("-") && arrayString[5].includes("-")) {
            if(!parseFloat(arrayString[1]) && !parseFloat(arrayString[2])) {
              msg.react('❎');
              msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nBooster 3\nBooster 4\nAdvertiser\nServer\nCustom Id\n\`\`\`\nPost screenshots of mail separate!');
              return;
            }
            if(arrayString[levellingAndPvPLength-2].toLowerCase().includes("balance") && msg.mentions.users.first()) {
              balancePayment(msg, "levelling");
            }
            if(arrayString[2] != "0") {
              if(arrayString[3].includes("Alliance-TC")) {
                arrayString[2] = parseFloat(arrayString[1]) * 0.15;
              } else {
                arrayString[2] = parseFloat(arrayString[1]) * 0.10;
              }
            }
            let spreadsheetId = euSheetId;
            let sheetName = 'Leveling!B2';
            let sheets = google.sheets('v4');
            var d = new Date();
            var curr_date = d.getDate();
            var curr_month = d.getMonth() + 1;
            arrayString[0] = curr_date + "/" + curr_month;
            if(arrayString[5] == "x-x") {
              arrayString[5] = "";
            }
            if(parseFloat(arrayString[1]) > 1000000) {
              msg.reply('Run entered, Pot is above 1mil gold please confirm with a screenshot of the gold below.');
            }
            arrayString[4] = arrayString[4].trim();
            arrayString[5] = arrayString[5].trim();
            arrayString[6] = arrayString[6].trim();
            var body = {
              values: [arrayString]
            };
            sheets.spreadsheets.values.append({
               auth: googleClient,
               spreadsheetId: spreadsheetId,
               range: sheetName,
               valueInputOption: "USER_ENTERED",
               resource: body
            }, function (err, response) {
               if (!err) {
                msg.react('✅');
                dbAdd('levelling', arrayString, 'eu', msg);
              } else {
                console.log(err);
              }
            });
          } else {
            msg.react('❎');
            msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nAdvertiser\nServer\nCustom Id\n\`\`\`\nIf there is no Booster 2 put an "x-x" instead. Post screenshots of mail separate!');
          }
        } else {
          msg.react('❎');
          msg.reply('Run Id has already been used, please pick another code.');
        }
      }
    }
  });
}

function pvpAdd(msg) {
  fullString = msg.content;
  var arrayString = fullString.split('\n');
  count = arrayString.length;
  runId = arrayString[levellingAndPvPLength-1];
  var sql = "SELECT * FROM pvp WHERE id_from_sheet = '"+ runId + "'";
  connection.query(sql, function (err, result) {
    if (err) throw err;
    console.log("Number of records: " + result.length + " for id: " + runId);
    if(msg.attachments.length != 1) {
      if(count != 1 && !checkURL(fullString)) {
        if(result.length == 0) {
          if(count == levellingAndPvPLength && arrayString[4].includes("-") && arrayString[6].includes("-") && arrayString[5].includes("-")) {
            if(!parseFloat(arrayString[1]) && !parseFloat(arrayString[2])) {
              msg.react('❎');
              msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nBooster 3\nBooster 4\nAdvertiser\nServer\nCustom Id\n\`\`\`\nPost screenshots of mail separate!');
              return;
            }
            if(arrayString[levellingAndPvPLength-2].toLowerCase().includes("balance") && msg.mentions.users.first()) {
              balancePayment(msg, "pvp");
            }
            if(arrayString[2] != "0") {
              if(arrayString[3].includes("Alliance-TC")) {
                arrayString[2] = parseFloat(arrayString[1]) * 0.15;
              } else {
                arrayString[2] = parseFloat(arrayString[1]) * 0.10;
              }
            }
            let spreadsheetId = euSheetId;
            let sheetName = 'PvP!B2';
            let sheets = google.sheets('v4');
            var d = new Date();
            var curr_date = d.getDate();
            var curr_month = d.getMonth() + 1;
            arrayString[0] = curr_date + "/" + curr_month;
            if(arrayString[5] == "x-x") {
              arrayString[5] = "";
            }
            if(parseFloat(arrayString[1]) > 1000000) {
              msg.reply('Run entered, Pot is above 1mil gold please confirm with a screenshot of the gold below.');
            }
            arrayString[4] = arrayString[4].trim();
            arrayString[5] = arrayString[5].trim();
            arrayString[6] = arrayString[6].trim();
            var body = {
              values: [arrayString]
            };
            sheets.spreadsheets.values.append({
               auth: googleClient,
               spreadsheetId: spreadsheetId,
               range: sheetName,
               valueInputOption: "USER_ENTERED",
               resource: body
            }, function (err, response) {
               if (!err) {
                msg.react('✅');
                dbAdd('pvp', arrayString, 'eu', msg);
              } else {
                console.log(err);
              }
            });
          } else {
            msg.react('❎');
            msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nAdvertiser\nServer\nCustom Id\n\`\`\`\nIf there is no Booster 2 put an "x-x" instead. Post screenshots of mail separate!');
          }
        } else {
          msg.react('❎');
          msg.reply('Run Id has already been used, please pick another code.');
        }
      }
    }
  });
}

function mplusAdd(msg) {
  fullString = msg.content;
  var arrayString = fullString.split('\n');
  count = arrayString.length;
  runId = arrayString[mPlusLength-1];
  var sql = "SELECT * FROM mythic_plus WHERE id_from_sheet = '"+ runId + "'";
  connection.query(sql, function (err, result) {
    if (err) throw err;
    console.log("Number of records: " + result.length + " for id: " + runId);
    if(msg.attachments.length != 1) {
      if(count != 1 && !checkURL(fullString)) {
        if(result.length == 0) {
          if(count == mPlusLength && arrayString[4].includes("-") && arrayString[5].includes("-") && arrayString[6].includes("-") && arrayString[7].includes("-") && arrayString[8].includes("-")) {
            if(!parseFloat(arrayString[1]) && !parseFloat(arrayString[2])) {
              msg.react('❎');
              msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nBooster 3\nBooster 4\nAdvertiser\nServer\nCustom Id\n\`\`\`\nPost screenshots of mail separate!');
              return;
            }
            if(arrayString[mPlusLength-2].toLowerCase().includes("balance") && msg.mentions.users.first()) {
              balancePayment(msg, "mythic_plus");
            }
            if(arrayString[2] != "0") {
              if(arrayString[3].includes("Alliance-TC")) {
                arrayString[2] = parseFloat(arrayString[1]) * 0.15;
              } else {
                arrayString[2] = parseFloat(arrayString[1]) * 0.10;
              }
            }
            let spreadsheetId = euSheetId;
            let sheetName = 'M+!B:L';
            let sheets = google.sheets('v4');
            var d = new Date();
            var curr_date = d.getDate();
            var curr_month = d.getMonth() + 1;
            arrayString[0] = curr_date + "/" + curr_month;
            if(parseFloat(arrayString[1]) > 1000000) {
              msg.reply('Run entered, Pot is above 1mil gold please confirm with a screenshot of the gold below.');
            }
            arrayString[4] = arrayString[4].trim();
            arrayString[5] = arrayString[5].trim();
            arrayString[6] = arrayString[6].trim();
            arrayString[7] = arrayString[7].trim();
            arrayString[8] = arrayString[8].trim();
            var body = {
              values: [arrayString]
            };
            sheets.spreadsheets.values.append({
               auth: googleClient,
               spreadsheetId: spreadsheetId,
               range: sheetName,
               valueInputOption: "USER_ENTERED",
               resource: body
            }, function (err, response) {
               if (!err) {
                msg.react('✅');
                dbAdd('mythic_plus', arrayString, 'eu', msg);
              } else {
                console.log(err);
              }
            });
          } else {
            msg.react('❎');
            msg.reply('Expected Format\n\`\`\`\nDate\nPrice\nAdcut\nFaction\nBooster 1\nBooster 2\nBooster 3\nBooster 4\nAdvertiser\nServer\nCustom Id\n\`\`\`\nPost screenshots of mail separate!');
          }
        } else {
          msg.react('❎');
          msg.reply('Run Id has already been used, please pick another code.');
        }
      }
    }
  });
}

function dbAdd(tableName, data, region, msg) {
  boosterCount = 0;
  if(tableName == "mythic_plus") {
    columns = "(date, pot, ad_cut, faction, booster_1, booster_2, booster_3, booster_4, advertiser, realm, id_from_sheet, booster_cut, collected, region)";
    removePercentHorde = 0.75;
    if(data[3] == "Alliance-BR") {
      removePercentAlliance = 0.725;
    } else {
      removePercentAlliance = 0.75;
    }
    if(data[4]) {
      boosterCount++;
    }
    if(data[5]) {
      boosterCount++;
    }
    if(data[6]) {
      boosterCount++;
    }
    if(data[7]) {
      boosterCount++;
    }
  } else if (tableName == "levelling") {
    columns = "(date, pot, ad_cut, faction, booster_1, booster_2, advertiser, realm, id_from_sheet, booster_cut, collected, region)";
    removePercentHorde = 0.8;
    removePercentAlliance = 0.775;
    if(data[4]) {
      boosterCount++;
    }
    if(data[5]) {
      boosterCount++;
    }
  } else if (tableName == "pvp") {
    columns = "(date, pot, ad_cut, faction, booster_1, booster_2, advertiser, realm, id_from_sheet, booster_cut, collected, region)";
    removePercentHorde = 0.8;
    removePercentAlliance = 0.8;
    if(data[4]) {
      boosterCount++;
    }
    if(data[5]) {
      boosterCount++;
    }
  }
  if(data[3].includes("Alliance-TC") !== -1) {
    data.push((parseFloat(data[1])*removePercentAlliance)/boosterCount);
  } else {
    data.push((parseFloat(data[1])*removePercentHorde)/boosterCount);
  }

  data.push("false");
  data.push(region);
  var sql = "INSERT INTO "+ tableName +" "+ columns +" VALUES ?";
  var values = [
    data
  ];
  connection.query(sql, [values], function (err, result) {
    if (err) console.log(err);
    console.log("Number of records inserted: " + result.affectedRows + " for id: " + data[data.length-4]);
  });

  messageAccepted(msg, data);
}

function dbDelete(tableName, id) {
  var sql = "DELETE FROM "+ tableName +" WHERE id_from_sheet = '"+ id + "'";
  connection.query(sql, function (err, result) {
    if (err) console.log(err);
    console.log("Number of records deleted: " + result.affectedRows + " for id: " + id);
  });
}

function checkURL(text) {
  return new RegExp("([a-zA-Z0-9]+://)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(/.*)?").test(text)
}

function balancePayment(message, type) {
  fullString = message.content;
  arrayString = fullString.split('\n');
  client.users.fetch(message.mentions.users.first().id).then((user) => {
      if(arrayString[2] == 0) {
        if(arrayString[3].includes("Alliance")) {
            arrayString[1] = parseFloat(arrayString[1]) * 0.85;
          } else {
            arrayString[1] = parseFloat(arrayString[1]) * 0.9;
          }
      }
      user.send("Hi, We have removed " + arrayString[1] + " gold from your balance to pay for a boost you have just purchased, If this comes as a suprise contact management immediately!");

      message.guild.members.fetch(message.mentions.users.first().id).then(function(guildUser) {
        try {
          let spreadsheetId = euSheetId;
          let sheetName = 'AddMinus';
          let sheets = google.sheets('v4');

          var promise = new Promise(function(resolve, reject) {
            request = {
              spreadsheetId: euSheetId,
              range: sheetName,
              auth: googleClient,
            };

            const response = sheets.spreadsheets.values.get(request);
            if (response) {
              resolve(response);
            } else {
              reject(response);
            }
          });

          promise.then(function(result) {
            addRow = result.data.values.length + 3;
            var body = {
              range: "Balance!M" + addRow + ":Q" + addRow,
              values: [[
                guildUser.nickname.replace(/\|(.*?)\|/, '').trim(),
                '-' + arrayString[1],
                arrayString[0],
                type + " purchase (" + arrayString[arrayString.length - 1] + ")",
                "Bot",
              ]]
            };

            sheets.spreadsheets.values.append({
               auth: googleClient,
               spreadsheetId: spreadsheetId,
               range: "Balance!M" + addRow + ":Q" + addRow,
               valueInputOption: "USER_ENTERED",
               resource: body
            }, function (err, response) {
              if (err) {
                console.log(err);
              } else {
                balancePaid(arrayString[arrayString.length - 1], type);
              }
            });
          });
        } catch(err) {
          console.log(err);
        }
      });
  });
}

function balancePaid(id, tableName) {
  var sql = "UPDATE "+ tableName +" SET collected = 'TRUE' WHERE id_from_sheet = '"+ id +"'";
  connection.query(sql, function (err, result) {
    if (err) console.log(err);
    console.log("Number of records changed: " + result.affectedRows + " for id: " + id);
  });
}

function balanceRemove(message) {
  fullString = message.content;
  arrayString = fullString.split('\n');
  var runId = arrayString[arrayString.length - 1];

  try {
    let spreadsheetId = euSheetId;
    let sheetName = 'AddMinus';
    let sheets = google.sheets('v4');

    var promise = new Promise(function(resolve, reject) {
      request = {
        spreadsheetId: euSheetId,
        range: sheetName,
        auth: googleClient,
      };

      const response = sheets.spreadsheets.values.get(request);
      if (response) {
        resolve(response);
      } else {
        reject(response);
      }
    });

    promise.then(function(result) {

      var returnKey = -1;
      result.data.values.forEach(function(info, index) {
        if(info[3]) {
          if (info[3].includes(runId)) {
             returnKey = index;
          };
        }
      });

      if(returnKey >= 0) {
        returnKey = returnKey + 3;
        sheets.spreadsheets.values.clear({
           auth: googleClient,
           spreadsheetId: spreadsheetId,
           range: "Balance!M" + returnKey + ":Q" + returnKey,
        }, function (err, response) {
          if (err) {
            console.log(err);
          } else {
            console.log(response);
          }
        });
      }
    });
  } catch(err) {
    console.log(err);
  }
}
