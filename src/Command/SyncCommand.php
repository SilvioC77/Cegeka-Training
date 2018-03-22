<?php

namespace App\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Hotel;
use App\Entity\Flight;

class SyncCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'SyncCommand';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }
/*        $ch = curl_init();
        $username = 'api';
        $password = 'api';

        curl_setopt($ch, CURLOPT_URL, "http://192.168.56.5:80/api/hotels.json");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        print_r($data);*/
        $this->em = $this->getContainer()->get('doctrine')->getManager();
        $agencies = $this->em->getRepository("App:Agency")->findAll();

        foreach($agencies as $agency) {

            $this->syncHotels($agency, $io);
            $this->syncFlights($agency, $io);
        }

        $io->success('Data synchronized from '.count($agencies).' agencies');

    }

    private function syncHotels($agency, $io)
    {
        $ch = curl_init();
        $username = 'api';
        $password = 'api';

        $url = $agency->getUrl()."/hotels.json";

        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        $io->writeln("Requesting $url");

        if ($data) {
            foreach($data as $hotel) {
                if ($hotel->owned) {
                    $entity = $this->em->getRepository("App:Hotel")->findOneBy(array('agency' => $agency, 'remoteId' => $hotel->id));
                    if (!$entity) {
                        $entity = new Hotel();
                        $entity->setAgency($agency);
                        $entity->setRemoteid($hotel->id);
                    }
                    $entity->setName($hotel->name);
                    $entity->setLocation($hotel->location);
                    $entity->setStart(new \DateTime($hotel->start));
                    $entity->setEnd(new \DateTime($hotel->end));
                    $entity->setStars($hotel->stars);
                    $entity->setPrice($hotel->price);
                    $entity->setOwned(false);

                    $this->em->persist($entity);
                }
            }
            $io->writeln("Test");
            foreach($agency->getHotels() as $agHot){
                $flag = false;
                foreach($data as $myData){
                    if($agHot->getRemoteId() == $myData->id){
                        $flag = true;
                    }
                }
                if($flag==false){
                    $this->em->remove($agHot);
                }
            }
            $this->em->flush();
        } else {
            $io->error("Could not decode json from $url");
        }
    }

    private function syncFlights($agency, $io)
    {
        $url = $agency->getUrl()."/flights.json";

        $ch = curl_init();
        $username = 'api';
        $password = 'api';

        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json')); // Assuming you're requesting JSON
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $response = curl_exec($ch);

        // If using JSON...
        $data = json_decode($response);

        $io->writeln("Requesting $url");

        if ($data) {
            foreach($data as $flight) {
                if ($flight->owned) {
                    $entity = $this->em->getRepository("App:Flight")->findOneBy(array('agency' => $agency, 'remoteId' => $flight->id));
                    if (!$entity) {
                        $entity = new Flight();
                        $entity->setAgency($agency);
                        $entity->setRemoteId($flight->id);
                    }
                    $entity->setAirline($flight->airline);
                    $entity->setFlightFrom($flight->flightfrom);
                    $entity->setFlightTo($flight->flightto);
                    $entity->setStart(new \DateTime($flight->start));
                    $entity->setEnd(new \DateTime($flight->end));
                    $entity->setDuration($flight->duration);
                    $entity->setTimeofday(new \DateTime($flight->timeofday));
                    $entity->setPrice($flight->price);
                    $entity->setOwned(false);

                    $this->em->persist($entity);
                }

                foreach($agency->getFlights() as $agFli){
                    $flag = false;
                    foreach($data as $myData){
                        if($agFli->getRemoteId() == $myData->id){
                            $flag = true;
                        }
                    }
                    if($flag==false){
                        $this->em->remove($agFli);
                    }
                }
            }

            $this->em->flush();
        } else {
            $io->error("Could not decode json from $url");
        }
    }
}
