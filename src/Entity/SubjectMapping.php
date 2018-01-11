<?php
/**
 * Created by PhpStorm.
 * User: ant
 * Date: 11.01.2018
 * Time: 2:49
 */

namespace Tallanto\Api\Entity;


class SubjectMapping
{
  const TYPE_LEASE = 'lease';
  const TYPE_YOGA_GROUP = 'yoga_group';
  const TYPE_YOGA_INDIVIDUAL = 'yoga_individual';
  const TYPE_SPECIALIST = 'specialist';
  const TYPE_PRAXIS = 'praxis';
  const TYPE_MASTERCLASS = 'masterclass';
  const TYPE_OTHER = 'other';

  private static $mapping = [
    'eaff779f-0a35-4d72-4969-534659a5f337' => self::TYPE_LEASE,
    //Аренда
    'b24c04e0-e5d8-c4cf-b383-531632555828' => self::TYPE_YOGA_GROUP,
    //Арт-терапия для детей от 4 до 7 лет
    '6cb577d1-3971-ce85-a2fc-5316328132a9' => self::TYPE_YOGA_GROUP,
    //Арт-терапия для детей от 7 до 11 лет
    'c10e3f3c-7acf-9b39-eef0-57271d31ead3' => self::TYPE_SPECIALIST,
    //Астролог
    '6c185440-bc5c-7dd5-013f-53163378b168' => self::TYPE_YOGA_GROUP,
    //Аштанга-виньяса йога
    'e6584e35-b8b8-eff8-3d93-55daddbe0d90' => self::TYPE_OTHER,
    //Биотрансформация
    'f38bc805-250e-6dd5-9a3b-580774924907' => self::TYPE_YOGA_GROUP,
    //Вводное занятие по Каула-йоге
    '746628c6-2124-3db7-f484-533e5e239005' => self::TYPE_OTHER,
    //Восточные танцы
    'c0ee2308-6dbc-523a-ae10-5811bdd79863' => self::TYPE_YOGA_GROUP,
    //Гонг медитация
    'ca1888a3-0d9b-f96c-8ce8-53163092a26e' => self::TYPE_OTHER,
    //Диагностическое занятие
    'e008e74b-a336-e2da-a30d-53163224cec2' => self::TYPE_YOGA_GROUP,
    //Женские практики
    '49c939e4-a2a0-e11d-a78a-5333dca9d0e7' => self::TYPE_YOGA_GROUP,
    //Женское здоровье
    '69446cde-af00-2260-49b0-52ed3320dcb0' => self::TYPE_YOGA_INDIVIDUAL,
    //Индивидуальное
    'aabb3591-bcb0-8c68-73f8-5640743f8766' => self::TYPE_OTHER,
    //Индивидуальное диагностирование
    '9cbecd7a-b177-5552-cc87-57e2935d22a8' => self::TYPE_YOGA_GROUP,
    //Интенсив с наставником
    'ed28de1a-d0e3-f212-06a1-5316300b31a7' => self::TYPE_YOGA_GROUP,
    //Йога 23
    '890583a6-1301-a7d7-2d70-531630fd9694' => self::TYPE_YOGA_GROUP,
    //Йога для беременных
    '3be130fe-ff0c-c08b-2e82-531631095257' => self::TYPE_YOGA_GROUP,
    //Йога для детей до 1 года
    'aac2bae5-5983-bafe-6878-5316328fac0f' => self::TYPE_YOGA_GROUP,
    //Йога для детей от 1 года до 4 лет
    '8888cba2-21bc-8108-7fd6-531632029d9b' => self::TYPE_YOGA_GROUP,
    //Йога для детей от 4 до 7 лет
    'f2a9fe97-a540-39f5-6e6d-5316322c7b3d' => self::TYPE_YOGA_GROUP,
    //Йога для детей от 7 до 11 лет
    'a7074025-93f1-0dba-5e54-533d389d0295' => self::TYPE_YOGA_GROUP,
    //Йога для начинающих
    '411dcad4-d85f-21ca-1b87-54344939e08e' => self::TYPE_YOGA_GROUP,
    //Йога лица
    '182d8ad5-af26-c79f-1c2d-531631860cda' => self::TYPE_YOGA_GROUP,
    //Йога Нидра
    '4b35b6a5-e38f-e098-2728-54610f614b9f' => self::TYPE_YOGA_GROUP,
    //Йога оргазма
    'e0be8f42-8bcb-310a-c1a2-559bf711699a' => self::TYPE_YOGA_GROUP,
    //Йога похудения и детоксикации
    '222dd7d0-2150-7d63-7c89-531633d9a57f' => self::TYPE_YOGA_GROUP,
    //Йога с партнером
    '3f38c668-2333-c9b6-f83d-531631deb18c' => self::TYPE_YOGA_GROUP,
    //Йога смеха
    '76f209e3-74ff-5431-8e7e-53163152acef' => self::TYPE_YOGA_GROUP,
    //Йогатерапия
    '440fe63a-e224-d23d-3313-52ecf38f02bc' => self::TYPE_YOGA_GROUP,
    //Каула-йога Начальный
    '80a006a3-dc77-04a6-daea-52ed17fe3c82' => self::TYPE_YOGA_GROUP,
    //Каула-йога Средний
    'df432e76-0925-90ee-88d1-549bbc33a608' => self::TYPE_OTHER,
    //Кино вечер
    'bef431f5-ca53-74e0-2cb4-5316323c3234' => self::TYPE_YOGA_GROUP,
    //Кундалини йога
    'ed5db38a-b2cd-427c-d3c3-55e3fa70ca2c' => self::TYPE_OTHER,
    //Майсор
    'b17832b5-f6d4-e208-969e-533af39f913a' => self::TYPE_SPECIALIST,
    //Массаж 
    '62c2a4fd-f76e-90cc-483b-5778ae00bb92' => self::TYPE_OTHER,
    //Мехенди
    '1dae90ef-b81c-271a-44a4-54610f66dcce' => self::TYPE_MASTERCLASS,
    //МК 108 сурий намаскар
    '5644c384-8c98-7805-a9e7-5633a01c59fb' => self::TYPE_MASTERCLASS,
    //МК Арома йога
    '8678a3ce-b036-39ef-ffe9-5633a1d2c8a2' => self::TYPE_MASTERCLASS,
    //МК Аюрведа и йога
    '516f05a5-759c-effc-dc3e-54b3f234c34e' => self::TYPE_MASTERCLASS,
    //МК Безмыслие
    'f1ee5368-12ef-650f-bbaf-54610f51c892' => self::TYPE_MASTERCLASS,
    //МК Гонг медитация
    '5a94f8cd-74a9-19f0-0d4a-54b3f2aead62' => self::TYPE_MASTERCLASS,
    //МК Женский комплекс
    '5b4aa745-86f8-3c1b-3b16-549bbc459db3' => self::TYPE_MASTERCLASS,
    //МК Йога для глаз
    '830d2a6b-fc85-e53f-af53-545e47a40f05' => self::TYPE_MASTERCLASS,
    //МК Йога лица
    'e10efe90-3bd8-7cd3-edea-5633a3d3c6d7' => self::TYPE_MASTERCLASS,
    //МК Йога очищения
    'ddb50d3a-f550-659b-c88e-54610f9e10bd' => self::TYPE_MASTERCLASS,
    //МК Йога смеха
    'aaafd81d-aaef-c834-35ff-549bbccc8991' => self::TYPE_MASTERCLASS,
    //МК Карта желаний
    '1bdfc40a-7849-f31e-223a-54d39c9209f8' => self::TYPE_MASTERCLASS,
    //МК Любовь к себе
    'b78fa5a8-9d9d-b8ef-019d-54b3f24fe37d' => self::TYPE_MASTERCLASS,
    //МК Материализация мыслей
    '4000069c-c23f-d0ff-b321-54d39c81ceaf' => self::TYPE_MASTERCLASS,
    //МК Материальное благополучие
    '562c3853-98dc-97a8-9496-549bbd8c50ca' => self::TYPE_MASTERCLASS,
    //МК Мужская йога
    '20db349e-5451-db57-06b2-549bbda4e061' => self::TYPE_MASTERCLASS,
    //МК Ом-медитация
    '1bc51408-6f78-f8fa-f42b-54b3f7c90f7c' => self::TYPE_MASTERCLASS,
    //МК Осанка
    '49542e43-30cc-4d05-7d51-5633a002e497' => self::TYPE_MASTERCLASS,
    //МК очищение от негатива
    '9e27b681-bb6b-dcac-d244-546110d8613a' => self::TYPE_MASTERCLASS,
    //МК Пранаяма
    '5f14fa48-daa0-b1ee-d81f-549bbd845359' => self::TYPE_MASTERCLASS,
    //МК Преодоление страхов
    '5c1a5737-052b-f797-d98a-546110b0a753' => self::TYPE_MASTERCLASS,
    //МК Саундфлоу
    '3b055ee0-5f0c-cf28-c50f-54b3f355a508' => self::TYPE_MASTERCLASS,
    //МК Сверхспособности
    '4f627daf-9a44-0d54-04fa-54610fb399a3' => self::TYPE_MASTERCLASS,
    //МК Тайский массаж
    '348e0954-3342-d09e-88cc-54610f4d2f6b' => self::TYPE_MASTERCLASS,
    //МК Управление голосом
    '82e8858c-b08d-796e-b56e-5633a0f369fc' => self::TYPE_MASTERCLASS,
    //МК Чакровая система
    '8d472b5b-62d2-1f88-05ad-54610fb5f0d4' => self::TYPE_MASTERCLASS,
    //МК Шавасана
    '4bfd30ea-0a2c-9a02-3c5d-5422b15c9f4e' => self::TYPE_OTHER,
    //МП Банный день
    '90e461a3-b267-6e2b-d690-533e9391346d' => self::TYPE_OTHER,
    //МП Жп-интенсив
    'd11c11a4-a54a-d136-0b9e-533e937c2343' => self::TYPE_OTHER,
    //МП Каула-интенсив
    'ed900532-ea18-cf42-3208-5461bdc120fb' => self::TYPE_OTHER,
    //МП Оливье
    '4474bb8d-d9e4-9f86-4869-56a86945ae95' => self::TYPE_OTHER,
    //Обучение медитации
    'be9fb551-75d7-9aa2-1829-544573cecd57' => self::TYPE_OTHER,
    //Пилатес
    '1a3d8b21-480d-1051-a5e4-53639795edc1' => self::TYPE_OTHER,
    //Подарок
    '8ed3d600-f067-3310-6d30-5811bd2cca88' => self::TYPE_OTHER,
    //Пранаяма
    'd4ca989a-07ac-517e-9ab6-5343d2dadbc9' => self::TYPE_PRAXIS,
    //Прием Дунаевского
    '712486de-8c14-bda8-c03f-533d5597b01f' => self::TYPE_SPECIALIST,
    //Прием остеопата
    '52dda5d4-68ec-20af-982a-5363974e1519' => self::TYPE_PRAXIS,
    //Прием\Сотрудник
    '96d18b27-6acf-f43c-4fd9-534659290865' => self::TYPE_OTHER,
    //Семинар
    'c33473c4-53f6-4951-7d8a-533e942e3703' => self::TYPE_OTHER,
    //Семинар для начинающих
    '32486568-8583-55ce-d166-5631ebdeaa5f' => self::TYPE_OTHER,
    //Спецгруппа
    '6c8824fe-aad6-715e-3f14-55c325638099' => self::TYPE_OTHER,
    //Спецпрограмма
    '7a32d753-47c1-3ceb-3d78-588f31d4abc6' => self::TYPE_OTHER,
    //Сурья намаскар
    '5c30a6d9-e425-25c4-234c-58c13f0bcacd' => self::TYPE_SPECIALIST,
    //Таролог
    'e8aaad11-0183-a77d-ad07-54a248222a81' => self::TYPE_OTHER,
    //Тестовое занятие
    '6a865875-3267-c070-6f72-531631d1417f' => self::TYPE_YOGA_GROUP,
    //Хатха-йога
    '511c8ccb-ecc3-da77-acf9-53163020b0c8' => self::TYPE_YOGA_GROUP,
    //Хот-йога
    'e37ab11e-7258-571a-6b18-5333e059b892' => self::TYPE_OTHER,
    //Чайный семинар
    'e52ddbe4-4137-52fc-342c-531632e3cbbe' => self::TYPE_YOGA_GROUP,
    //Час силы
    'd5a9527b-28fa-d8a2-8db2-540f22dc571a' => self::TYPE_OTHER,
    //Ян-Цигун'
  ];

  /**
   * Returns subject type by ID.
   *
   * @param string $subjectId
   * @return string
   */
  public static function getType($subjectId)
  {
    return isset(self::$mapping[$subjectId]) ? self::$mapping[$subjectId] : 'undefined';
  }
}