<?php

namespace GlobalMoo;

use GlobalMoo\Request\CreateModel;
use GlobalMoo\Request\CreateProject;
use GlobalMoo\Request\LoadInverseOutput;
use GlobalMoo\Request\LoadObjectives;
use GlobalMoo\Request\LoadOutputCases;
use GlobalMoo\Request\ReadModel;
use GlobalMoo\Request\ReadModels;
use GlobalMoo\Request\ReadObjective;
use GlobalMoo\Request\RegisterAccount;
use GlobalMoo\Request\SuggestInverse;

interface ClientInterface
{

    public function registerAccount(RegisterAccount $request): Account;

    /**
     * @return list<Model>
     */
    public function readModels(?ReadModels $request = null): array;
    public function readModel(ReadModel $request): Model;
    public function createModel(CreateModel $request): Model;
    public function createProject(CreateProject $request): Project;
    public function loadOutputCases(LoadOutputCases $request): Trial;
    public function loadObjectives(LoadObjectives $request): Objective;
    public function readObjective(ReadObjective $request): Objective;
    public function suggestInverse(SuggestInverse $request): Inverse;
    public function loadInverseOutput(LoadInverseOutput $request): Inverse;

}
