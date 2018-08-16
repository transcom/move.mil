import React, { Component } from 'react';
import Rooms from './components/rooms';
import Total from './components/total';
import * as _ from 'lodash';
import * as axios from 'axios';

class App extends Component {
  constructor(){
    super();

    this.baseUrl = process.env.NODE_ENV === 'development' ? 'http://move.mil.localhost:8000/' : '/';
    this.state = {
      rooms: null,
      totalEstimate: 0,
      totalQuantity: 0,
      isFixed: true
    };
  }

  componentDidMount = () =>{
    let url = `${this.baseUrl}parser/weight_calculator`;

    // axios.get(url)
    //   .then(res => {
    //     let data = res.data;
        let data = {
          "Bedrooms": {
              "displayName": "Bedrooms",
              "icon": "themes/custom/move_mil/assets/img/icons/bedroom.svg",
              "items": {
                  "WardrobeCartonsClothes": {
                      "displayName": "Wardrobe Cartons/Clothes",
                      "weight": 45
                  },
                  "BasketClothes": {
                      "displayName": "Basket (Clothes)",
                      "weight": 35
                  },
                  "ACWindow": {
                      "displayName": "AC (Window)",
                      "weight": 140
                  },
                  "ChaiseLounge": {
                      "displayName": "Chaise Lounge",
                      "weight": 175
                  },
                  "DresserDouble": {
                      "displayName": "Dresser (Double)",
                      "weight": 350
                  },
                  "NightTable": {
                      "displayName": "Night Table",
                      "weight": 35
                  },
                  "Valet": {
                      "displayName": "Valet",
                      "weight": 21
                  },
                  "BedTrundle": {
                      "displayName": "Bed (Trundle)",
                      "weight": 350
                  },
                  "BedBunkSet2": {
                      "displayName": "Bed (Bunk Set (2))",
                      "weight": 490
                  },
                  "WardrobeSmall": {
                      "displayName": "Wardrobe (Small)",
                      "weight": 140
                  },
                  "BedSingle": {
                      "displayName": "Bed (Single)",
                      "weight": 280
                  },
                  "RugSmallPad": {
                      "displayName": "Rug (Small/Pad)",
                      "weight": 21
                  },
                  "CedarChest": {
                      "displayName": "Cedar Chest",
                      "weight": 105
                  },
                  "RugLargePad": {
                      "displayName": "Rug (Large/Pad)",
                      "weight": 70
                  },
                  "BedRollaway": {
                      "displayName": "Bed (Rollaway)",
                      "weight": 140
                  },
                  "BookshelvesSect": {
                      "displayName": "Bookshelves (Sect)",
                      "weight": 35
                  },
                  "BedHideaway": {
                      "displayName": "Bed (Hideaway)",
                      "weight": 280
                  },
                  "BedDoubleFull": {
                      "displayName": "Bed (Double/Full)",
                      "weight": 420
                  },
                  "BedKing": {
                      "displayName": "Bed (King)",
                      "weight": 490
                  },
                  "DresserBench": {
                      "displayName": "Dresser (Bench)",
                      "weight": 21
                  },
                  "BureauDresser": {
                      "displayName": "Bureau (Dresser)",
                      "weight": 210
                  },
                  "Drawers": {
                      "displayName": "Drawers",
                      "weight": 175
                  },
                  "ChestBoudoir": {
                      "displayName": "Chest (Boudoir)",
                      "weight": 70
                  },
                  "WaterBed": {
                      "displayName": "Water Bed",
                      "weight": 420
                  },
                  "Daybed": {
                      "displayName": "Daybed",
                      "weight": 210
                  },
                  "VanityDresser": {
                      "displayName": "Vanity Dresser",
                      "weight": 140
                  },
                  "BedQueen": {
                      "displayName": "Bed (Queen)",
                      "weight": 450
                  },
                  "Armoire": {
                      "displayName": "Armoire",
                      "weight": 210
                  },
                  "DeskSmall": {
                      "displayName": "Desk (Small)",
                      "weight": 154
                  },
                  "WardrobeLarge": {
                      "displayName": "Wardrobe (Large)",
                      "weight": 280
                  },
                  "LampFloorPole": {
                      "displayName": "Lamp (Floor/Pole)",
                      "weight": 21
                  }
              }
          },
          "Garage": {
              "displayName": "Garage",
              "icon": "themes/custom/move_mil/assets/img/icons/garage.svg",
              "items": {
                  "SawHorse": {
                      "displayName": "Saw Horse",
                      "weight": 35
                  },
                  "VacuumCleaner": {
                      "displayName": "Vacuum Cleaner",
                      "weight": 35
                  },
                  "Humidifier": {
                      "displayName": "Humidifier",
                      "weight": 35
                  },
                  "MiniRefrigerator": {
                      "displayName": "Mini-Refrigerator",
                      "weight": 70
                  },
                  "Freezer15CUorLess": {
                      "displayName": "Freezer (15 CU or Less)",
                      "weight": 315
                  },
                  "Freezer16CUorMore": {
                      "displayName": "Freezer (16 CU or More)",
                      "weight": 420
                  },
                  "Dehumidifier": {
                      "displayName": "Dehumidifier",
                      "weight": 70
                  },
                  "PingPongTable": {
                      "displayName": "Ping Pong Table",
                      "weight": 140
                  },
                  "RowingMachine": {
                      "displayName": "Rowing Machine",
                      "weight": 70
                  },
                  "WeightBench": {
                      "displayName": "Weight Bench",
                      "weight": 105
                  },
                  "Scooter": {
                      "displayName": "Scooter",
                      "weight": 35
                  },
                  "WorkMate": {
                      "displayName": "Work Mate",
                      "weight": 70
                  },
                  "CotFolding": {
                      "displayName": "Cot (Folding)",
                      "weight": 70
                  },
                  "SewingMachine": {
                      "displayName": "Sewing Machine",
                      "weight": 70
                  },
                  "Treadmill": {
                      "displayName": "Treadmill",
                      "weight": 50
                  },
                  "FloorPolisher": {
                      "displayName": "Floor Polisher",
                      "weight": 21
                  },
                  "ToolChest": {
                      "displayName": "Tool Chest",
                      "weight": 70
                  },
                  "PoolTable": {
                      "displayName": "Pool Table",
                      "weight": 280
                  },
                  "MetalShelvesSection": {
                      "displayName": "Metal Shelves/Section",
                      "weight": 35
                  },
                  "Fan": {
                      "displayName": "Fan",
                      "weight": 35
                  },
                  "CarCarrier": {
                      "displayName": "Car Carrier",
                      "weight": 70
                  },
                  "TableUtility": {
                      "displayName": "Table (Utility)",
                      "weight": 35
                  },
                  "StepLadder": {
                      "displayName": "Step Ladder",
                      "weight": 35
                  },
                  "ExerciseBike": {
                      "displayName": "Exercise Bike",
                      "weight": 105
                  },
                  "Tricycle": {
                      "displayName": "Tricycle",
                      "weight": 35
                  },
                  "ChairFolding": {
                      "displayName": "Chair (Folding)",
                      "weight": 7
                  },
                  "MotorElectric": {
                      "displayName": "Motor (Electric)",
                      "weight": 7
                  },
                  "BarbellSetSmall": {
                      "displayName": "Barbell Set (Small)",
                      "weight": 35
                  },
                  "CabinetFile": {
                      "displayName": "Cabinet (File)",
                      "weight": 140
                  },
                  "TackleBox": {
                      "displayName": "Tackle Box",
                      "weight": 7
                  },
                  "MovieScreen": {
                      "displayName": "Movie Screen",
                      "weight": 7
                  },
                  "Suitcase": {
                      "displayName": "Suitcase",
                      "weight": 35
                  },
                  "GolfBag": {
                      "displayName": "Golf Bag",
                      "weight": 14
                  },
                  "FootlockerTrunk": {
                      "displayName": "Footlocker/Trunk",
                      "weight": 35
                  },
                  "WagonChilds": {
                      "displayName": "Wagon (Child's)",
                      "weight": 35
                  },
                  "Bicycle": {
                      "displayName": "Bicycle",
                      "weight": 35
                  },
                  "BowlingBallBag": {
                      "displayName": "Bowling Ball/Bag",
                      "weight": 14
                  },
                  "SleepingBag": {
                      "displayName": "Sleeping Bag",
                      "weight": 7
                  },
                  "SkisPoles": {
                      "displayName": "Skis/Poles",
                      "weight": 21
                  },
                  "Sled": {
                      "displayName": "Sled",
                      "weight": 14
                  },
                  "MovieSlideProjector": {
                      "displayName": "Movie/Slide Projector",
                      "weight": 7
                  },
                  "CardTable": {
                      "displayName": "Card Table",
                      "weight": 7
                  },
                  "Moped": {
                      "displayName": "Moped",
                      "weight": 105
                  },
                  "HeaterGasElectric": {
                      "displayName": "Heater (Gas/Electric)",
                      "weight": 35
                  },
                  "WorkBench": {
                      "displayName": "Work Bench",
                      "weight": 140
                  },
                  "AshTrashCan": {
                      "displayName": "Ash/Trash Can",
                      "weight": 49
                  },
                  "PowerTools": {
                      "displayName": "Power Tools",
                      "weight": 140
                  }
              }
          },
          "PatioShed": {
              "displayName": "Patio & Shed",
              "icon": "themes/custom/move_mil/assets/img/icons/patio.svg",
              "items": {
                  "PlantStand": {
                      "displayName": "Plant Stand",
                      "weight": 70
                  },
                  "DogHouse": {
                      "displayName": "Dog House",
                      "weight": 70
                  },
                  "OutdoorSwings": {
                      "displayName": "Outdoor Swings",
                      "weight": 105
                  },
                  "LeafSweeper": {
                      "displayName": "Leaf Sweeper",
                      "weight": 35
                  },
                  "LawnMowerPower": {
                      "displayName": "Lawn Mower (Power)",
                      "weight": 105
                  },
                  "LawnMowerHand": {
                      "displayName": "Lawn Mower (Hand)",
                      "weight": 35
                  },
                  "Umbrella": {
                      "displayName": "Umbrella",
                      "weight": 35
                  },
                  "RollerLawn": {
                      "displayName": "Roller (Lawn)",
                      "weight": 105
                  },
                  "LawnEdger": {
                      "displayName": "Lawn Edger",
                      "weight": 35
                  },
                  "TablePicnic": {
                      "displayName": "Table (Picnic)",
                      "weight": 70
                  },
                  "Grill": {
                      "displayName": "Grill",
                      "weight": 70
                  },
                  "Spreader": {
                      "displayName": "Spreader",
                      "weight": 7
                  },
                  "CampStove": {
                      "displayName": "Camp Stove",
                      "weight": 14
                  },
                  "SnowBlower": {
                      "displayName": "Snow Blower",
                      "weight": 105
                  },
                  "SandBox": {
                      "displayName": "Sand Box",
                      "weight": 70
                  },
                  "GardenHoseTools": {
                      "displayName": "Garden Hose/Tools",
                      "weight": 70
                  },
                  "BirdBath": {
                      "displayName": "Bird Bath",
                      "weight": 35
                  },
                  "LadderExtension": {
                      "displayName": "Ladder (Extension)",
                      "weight": 70
                  },
                  "Wheelbarrow": {
                      "displayName": "Wheelbarrow",
                      "weight": 56
                  },
                  "LawnMowerRiding": {
                      "displayName": "Lawn Mower (Riding)",
                      "weight": 245
                  },
                  "PicnicBench": {
                      "displayName": "Picnic Bench",
                      "weight": 35
                  },
                  "GliderSettee": {
                      "displayName": "Glider/Settee",
                      "weight": 140
                  },
                  "ChairOutdoor": {
                      "displayName": "Chair (Outdoor)",
                      "weight": 70
                  },
                  "TVAntenna": {
                      "displayName": "TV Antenna",
                      "weight": 35
                  },
                  "HandtruckDolly": {
                      "displayName": "Hand truck/Dolly",
                      "weight": 14
                  },
                  "Settee": {
                      "displayName": "Settee",
                      "weight": 140
                  },
                  "RockerSwing": {
                      "displayName": "Rocker (Swing)",
                      "weight": 105
                  },
                  "OutdoorSlide": {
                      "displayName": "Outdoor Slide",
                      "weight": 70
                  },
                  "ChildsPool": {
                      "displayName": "Child's Pool",
                      "weight": 84
                  },
                  "PicnicTable": {
                      "displayName": "Picnic Table",
                      "weight": 140
                  }
              }
          },
          "Nursery": {
              "displayName": "Nursery",
              "icon": "themes/custom/move_mil/assets/img/icons/nursery.svg",
              "items": {
                  "ChairRocker": {
                      "displayName": "Chair (Rocker)",
                      "weight": 84
                  },
                  "CribBaby": {
                      "displayName": "Crib (Baby)",
                      "weight": 70
                  },
                  "Bassinet": {
                      "displayName": "Bassinet",
                      "weight": 35
                  },
                  "DollHouseSmall": {
                      "displayName": "Doll House (Small)",
                      "weight": 14
                  },
                  "PlayHouse": {
                      "displayName": "Play House",
                      "weight": 70
                  },
                  "CarSeat": {
                      "displayName": "Car Seat",
                      "weight": 14
                  },
                  "RugSmallPad": {
                      "displayName": "Rug (Small/Pad)",
                      "weight": 21
                  },
                  "BedYouth": {
                      "displayName": "Bed (Youth)",
                      "weight": 70
                  },
                  "RugLargePad": {
                      "displayName": "Rug (Large/Pad)",
                      "weight": 70
                  },
                  "Chest": {
                      "displayName": "Chest",
                      "weight": 91
                  },
                  "BabyCarriage": {
                      "displayName": "Baby Carriage",
                      "weight": 70
                  },
                  "ChestToy": {
                      "displayName": "Chest (Toy)",
                      "weight": 35
                  },
                  "BasketPlastic": {
                      "displayName": "Basket (Plastic)",
                      "weight": 21
                  },
                  "ChairChilds": {
                      "displayName": "Chair (Child's)",
                      "weight": 21
                  },
                  "TableChilds": {
                      "displayName": "Table (Child's)",
                      "weight": 35
                  },
                  "PlayPen": {
                      "displayName": "Play Pen",
                      "weight": 70
                  },
                  "Stroller": {
                      "displayName": "Stroller",
                      "weight": 35
                  },
                  "ChangingTable": {
                      "displayName": "Changing Table",
                      "weight": 70
                  }
              }
          },
          "DiningRoom": {
              "displayName": "Dining Room",
              "icon": "themes/custom/move_mil/assets/img/icons/dining-room.svg",
              "items": {
                  "CabinetCorner": {
                      "displayName": "Cabinet (Corner)",
                      "weight": 140
                  },
                  "TableDining": {
                      "displayName": "Table (Dining)",
                      "weight": 210
                  },
                  "RugSmallPad": {
                      "displayName": "Rug (Small/Pad)",
                      "weight": 21
                  },
                  "Buffet": {
                      "displayName": "Buffet",
                      "weight": 210
                  },
                  "Bench": {
                      "displayName": "Bench",
                      "weight": 70
                  },
                  "RugLargePad": {
                      "displayName": "Rug (Large/Pad)",
                      "weight": 70
                  },
                  "CabinetChina": {
                      "displayName": "Cabinet (China)",
                      "weight": 175
                  },
                  "ChairDining": {
                      "displayName": "Chair (Dining)",
                      "weight": 35
                  },
                  "TeaCart": {
                      "displayName": "Tea Cart",
                      "weight": 70
                  },
                  "Server": {
                      "displayName": "Server",
                      "weight": 105
                  },
                  "CabinetCurio": {
                      "displayName": "Cabinet (Curio)",
                      "weight": 105
                  },
                  "HutchBottom": {
                      "displayName": "Hutch (Bottom)",
                      "weight": 140
                  },
                  "HutchTop": {
                      "displayName": "Hutch (Top)",
                      "weight": 210
                  }
              }
          },
          "LivingFamilyRooms": {
              "displayName": "Living/Family Rooms",
              "icon": "themes/custom/move_mil/assets/img/icons/living-room.svg",
              "items": {
                  "BirdCageStand": {
                      "displayName": "Bird Cage/Stand",
                      "weight": 35
                  },
                  "ChairOccas": {
                      "displayName": "Chair (Occas)",
                      "weight": 105
                  },
                  "Sofa2Cushion": {
                      "displayName": "Sofa (2 Cushion)",
                      "weight": 245
                  },
                  "ChairStraight": {
                      "displayName": "Chair (Straight)",
                      "weight": 35
                  },
                  "ClockGrandfather": {
                      "displayName": "Clock (Grandfather)",
                      "weight": 140
                  },
                  "VideoGameSet": {
                      "displayName": "Video Game Set",
                      "weight": 35
                  },
                  "WineRack": {
                      "displayName": "Wine Rack",
                      "weight": 35
                  },
                  "TVStand": {
                      "displayName": "TV Stand",
                      "weight": 35
                  },
                  "PianoSpinet": {
                      "displayName": "Piano (Spinet)",
                      "weight": 420
                  },
                  "BookCaseSmall": {
                      "displayName": "Book Case (Small)",
                      "weight": 70
                  },
                  "SofaSectionPer": {
                      "displayName": "Sofa (Section Per)",
                      "weight": 210
                  },
                  "TVConsole": {
                      "displayName": "TV Console",
                      "weight": 105
                  },
                  "RecordPlayer": {
                      "displayName": "Record Player",
                      "weight": 14
                  },
                  "SpeakerStereo": {
                      "displayName": "Speaker (Stereo)",
                      "weight": 35
                  },
                  "RugSmallPad": {
                      "displayName": "Rug (Small/Pad)",
                      "weight": 21
                  },
                  "MagazineRack": {
                      "displayName": "Magazine Rack",
                      "weight": 14
                  },
                  "RugLargePad": {
                      "displayName": "Rug (Large/Pad)",
                      "weight": 70
                  },
                  "Sofa4Cushion": {
                      "displayName": "Sofa (4 Cushion)",
                      "weight": 420
                  },
                  "FishTank": {
                      "displayName": "Fish Tank",
                      "weight": 105
                  },
                  "StudioHidebed": {
                      "displayName": "Studio (Hidebed)",
                      "weight": 350
                  },
                  "DeskSmall": {
                      "displayName": "Desk (Small)",
                      "weight": 154
                  },
                  "PianoBabyGrand": {
                      "displayName": "Piano (Baby Grand)",
                      "weight": 490
                  },
                  "Footstool": {
                      "displayName": "Footstool",
                      "weight": 14
                  },
                  "RadioTable": {
                      "displayName": "Radio (Table)",
                      "weight": 14
                  },
                  "FireplaceEquipment": {
                      "displayName": "Fireplace Equipment",
                      "weight": 35
                  },
                  "TableCoffeeEnd": {
                      "displayName": "Table (Coffee/End)",
                      "weight": 35
                  },
                  "BenchPiano": {
                      "displayName": "Bench (Piano)",
                      "weight": 35
                  },
                  "Sofa3Cushion": {
                      "displayName": "Sofa (3 Cushion)",
                      "weight": 315
                  },
                  "ChairArm": {
                      "displayName": "Chair (Arm)",
                      "weight": 70
                  },
                  "BookshelvesSect": {
                      "displayName": "Bookshelves (Sect)",
                      "weight": 35
                  },
                  "ChairOverstuffed": {
                      "displayName": "Chair (Overstuffed)",
                      "weight": 175
                  },
                  "BarCart": {
                      "displayName": "Bar Cart",
                      "weight": 105
                  },
                  "TVTraySet": {
                      "displayName": "TV Tray Set",
                      "weight": 21
                  },
                  "PianoParlorGrand": {
                      "displayName": "Piano (Parlor Grand)",
                      "weight": 560
                  },
                  "Books": {
                      "displayName": "Books",
                      "weight": 45
                  },
                  "TableDropleaf": {
                      "displayName": "Table (Dropleaf)",
                      "weight": 84
                  },
                  "BookCaseWallUnit": {
                      "displayName": "Book Case/Wall Unit",
                      "weight": 140
                  },
                  "TVBigScreen": {
                      "displayName": "TV Big Screen",
                      "weight": 100
                  },
                  "LampFloorPole": {
                      "displayName": "Lamp (Floor/Pole)",
                      "weight": 21
                  },
                  "WallArt": {
                      "displayName": "Wall Art",
                      "weight": 15
                  },
                  "Pictures": {
                      "displayName": "Pictures",
                      "weight": 25
                  }
              }
          },
          "Kitchen": {
              "displayName": "Kitchen",
              "icon": "themes/custom/move_mil/assets/img/icons/kitchen.svg",
              "items": {
                  "Refrigerator10cuftorLess": {
                      "displayName": "Refrigerator (10 cu. ft. or Less)",
                      "weight": 315
                  },
                  "Refrigerator11cuftorMore": {
                      "displayName": "Refrigerator (11 cu. ft. or More)",
                      "weight": 420
                  },
                  "Range": {
                      "displayName": "Range",
                      "weight": 210
                  },
                  "Dishwasher": {
                      "displayName": "Dishwasher",
                      "weight": 140
                  },
                  "ChairHigh": {
                      "displayName": "Chair (High)",
                      "weight": 35
                  },
                  "MetalCart": {
                      "displayName": "Metal Cart",
                      "weight": 35
                  },
                  "DishSet": {
                      "displayName": "Dish Set",
                      "weight": 70
                  },
                  "Roaster": {
                      "displayName": "Roaster",
                      "weight": 35
                  },
                  "KitchenCabinet": {
                      "displayName": "Kitchen Cabinet",
                      "weight": 210
                  },
                  "Stool": {
                      "displayName": "Stool",
                      "weight": 21
                  },
                  "BulletinBoard": {
                      "displayName": "Bulletin Board",
                      "weight": 7
                  },
                  "BreakfastSetChairs": {
                      "displayName": "Breakfast Set (Chairs)",
                      "weight": 35
                  },
                  "ServingCart": {
                      "displayName": "Serving Cart",
                      "weight": 105
                  },
                  "UtilityCabinet": {
                      "displayName": "Utility Cabinet",
                      "weight": 70
                  },
                  "MicrowaveCart": {
                      "displayName": "Microwave Cart",
                      "weight": 70
                  },
                  "Microwave": {
                      "displayName": "Microwave",
                      "weight": 70
                  },
                  "BreakfastSetTable": {
                      "displayName": "Breakfast Set (Table)",
                      "weight": 70
                  },
                  "PotsandPans": {
                      "displayName": "Pots and Pans",
                      "weight": 35
                  },
                  "Silverware": {
                      "displayName": "Silverware",
                      "weight": 35
                  },
                  "ServingDishes": {
                      "displayName": "Serving Dishes",
                      "weight": 5
                  },
                  "StandMixer": {
                      "displayName": "Stand Mixer",
                      "weight": 20
                  }
              }
          },
          "Office": {
              "displayName": "Office",
              "icon": "themes/custom/move_mil/assets/img/icons/office.svg",
              "items": {
                  "PrinterKeyboard": {
                      "displayName": "Printer/Keyboard",
                      "weight": 5
                  },
                  "DeskOffice": {
                      "displayName": "Desk (Office)",
                      "weight": 210
                  },
                  "ComputerandAccessories": {
                      "displayName": "Computer and Accessories",
                      "weight": 35
                  },
                  "ChairStraight": {
                      "displayName": "Chair (Straight)",
                      "weight": 35
                  },
                  "DeskSecretary": {
                      "displayName": "Desk (Secretary)",
                      "weight": 245
                  },
                  "Books": {
                      "displayName": "Books",
                      "weight": 45
                  },
                  "BookshelvesSect": {
                      "displayName": "Bookshelves (Sect)",
                      "weight": 35
                  },
                  "HomeComputer": {
                      "displayName": "Home Computer",
                      "weight": 30
                  },
                  "WastePaperBasket": {
                      "displayName": "Waste Paper Basket",
                      "weight": 2
                  }
              }
          },
          "Laundry": {
              "displayName": "Laundry",
              "icon": "themes/custom/move_mil/assets/img/icons/laundry.svg",
              "items": {
                  "Dryer": {
                      "displayName": "Dryer",
                      "weight": 175
                  },
                  "IroningBoard": {
                      "displayName": "Ironing Board",
                      "weight": 21
                  },
                  "WashingMachine": {
                      "displayName": "Washing Machine",
                      "weight": 175
                  },
                  "BasketClothes": {
                      "displayName": "Basket (Clothes)",
                      "weight": 35
                  },
                  "RugSmallPad": {
                      "displayName": "Rug (Small/Pad)",
                      "weight": 21
                  },
                  "ClothesHamper": {
                      "displayName": "Clothes Hamper",
                      "weight": 35
                  },
                  "ClothesDryRack": {
                      "displayName": "Clothes Dry Rack",
                      "weight": 35
                  }
              }
          }
      };
        _.each(data, (room)=>{
          room.customItems = {};
          room.tempItem = {
            displayName: '',
            qty: 0,
            weight: 0
          }
        });
        this.setState({ rooms: data});
        this.appDivOffsetTop = this.appDiv.offsetTop;
        this.appDivHeight = this.appDiv.clientHeight;
    //})
  }

  setFixedState = () => { 
    let scrollPos = window.pageYOffset;
    let windowHeight = window.innerHeight;
    let stickyPos = this.appDivHeight + this.appDivOffsetTop;
    let isSticky = scrollPos < stickyPos;
    let _isFixed = null;

    isSticky = stickyPos > (scrollPos + windowHeight);

    if(isSticky && !this.state.isFixed){
      _isFixed = true;
    }

    if(!isSticky && this.state.isFixed){
      _isFixed = false;
    }

    if(_isFixed !== null){
      this.setState({
        isFixed: _isFixed
      });
    }
  }

  createUpdateTempItem = (roomKey, itemKey, value) => {
    let newState = this.state.rooms;
    let _tempItem;

    if(!newState[roomKey].tempItem){
      newState[roomKey].tempItem = {};
    }

    _tempItem = newState[roomKey].tempItem;

    if(!_tempItem[itemKey]){
      _tempItem[itemKey] = {};
    }

    _tempItem[itemKey] = value;

    this.setState({
      rooms: newState
    });
  }

  addNewItem = (roomKey) => {
    let newState = this.state.rooms;
    let newItem = newState[roomKey].tempItem;
    let id;

    if(!newState[roomKey].customItems){
      newState[roomKey].customItems = {};
    }

    id = getUniqueId();

    function getUniqueId(){
      let _id = `${roomKey}_${Math.floor(Math.random() * 10000)}`;
      return !newState[roomKey].customItems[_id] ? _id : getUniqueId();
    }

    newState[roomKey].customItems[id] = newItem;
    newState[roomKey].tempItem = {
      displayName: '',
      qty: 0,
      weight: 0,
      isFocus: true
    };

    this.setState({
      rooms: newState
    },  () => {
      this.appDivOffsetTop = this.appDiv.offsetTop;
      this.appDivHeight = this.appDiv.clientHeight;
      this.calculateRoomWeightTotals(roomKey);
    });
  }

  updateRoomQuanties = (updatedItem) => {
    let newState = this.state.rooms;
    newState[updatedItem.roomKey][updatedItem.itemType][updatedItem.itemKey][updatedItem.valKey] = updatedItem.val;

    this.setState({
      rooms: newState
    },  () => {
      this.calculateRoomWeightTotals(updatedItem.roomKey);
    });
  }

  calculateRoomWeightTotals = (roomKey) =>{
    let totalweight = 0;
    let totalQty = 0;
    _.each(this.state.rooms[roomKey].items, (item)=>{
      if(item.qty){
        totalweight += (parseInt(item.qty, 10) * item.weight);
        totalQty += parseInt(item.qty, 10);
      }
    });

    _.each(this.state.rooms[roomKey].customItems, (item)=>{
      if(item.qty){
        totalweight += (parseInt(item.qty, 10) * item.weight);
        totalQty += parseInt(item.qty, 10);
      }
    });

    this.setState({
      rooms: {...this.state.rooms, [roomKey]: {...this.state.rooms[roomKey], totalweight: totalweight, totalQty: totalQty}},
    },  () => {
      this.sumRoomTotals();
    });
  }
  
  sumRoomTotals = () =>{
    let totalEstimate = 0;
    let totalQuantity = 0;
    _.each(this.state.rooms, (room)=>{
      if(room.totalweight){
        totalEstimate += room.totalweight;
      }
      if(room.totalQty){
        totalQuantity += room.totalQty;
      }
    });

    this.setState({
      totalEstimate: totalEstimate,
      totalQuantity: totalQuantity
    })
  }

  render() {
    return (
      <div className="estimator-container"
           ref={(appDiv)=>{this.appDiv = appDiv;}}>
        <Rooms rooms={this.state.rooms}
               baseUrl={this.baseUrl}
               updateRoomQuanties={this.updateRoomQuanties} 
               createUpdateTempItem={this.createUpdateTempItem}
               addNewItem={this.addNewItem}/>
               
        <Total totalEstimate={this.state.totalEstimate}
               totalQuantity={this.state.totalQuantity}
               isFixed={this.state.isFixed}
               fixedFn={this.setFixedState}/>
      </div>
    );
  }
}

export default App;
