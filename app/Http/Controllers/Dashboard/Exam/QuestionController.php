<?php

namespace App\Http\Controllers\Dashboard\Exam;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Exam\QuestionRequest;
use App\Http\Services\Dashboard\Exam\QuestionService;
use App\Repository\ExamRepositoryInterface;
use App\Repository\IndicatorRepositoryInterface;
use App\Repository\QuestionRepositoryInterface;
use App\Repository\StandardRepositoryInterface;

class QuestionController extends Controller
{
    private ExamRepositoryInterface $examRepository;
    private IndicatorRepositoryInterface $indicatorRepository;
    private StandardRepositoryInterface $standardRepository;
    private QuestionRepositoryInterface $questionRepository;

    protected QuestionService $question;

    public function __construct(
        ExamRepositoryInterface $examRepository,
        IndicatorRepositoryInterface $indicatorRepository,
        StandardRepositoryInterface $standardRepository,
        QuestionRepositoryInterface $questionRepository,
        QuestionService $questionService,
    )
    {
        $this->examRepository = $examRepository;
        $this->indicatorRepository = $indicatorRepository;
        $this->standardRepository = $standardRepository;
        $this->questionRepository = $questionRepository;
        $this->question = $questionService;
    }

    public function create($examId)
    {
        $exam = $this->examRepository->getById($examId);
        $typeValues = property_exists($this, $exam->question_type.'Repository') ? $this->{$exam->question_type.'Repository'}->getAll() : null;
        return view('dashboard.site.questions.create', ['exam' => $exam, 'typeValues' => $typeValues]);
    }

    public function store(QuestionRequest $request, $examId)
    {
        return $this->question->store($request, $examId);
    }

    public function edit($examId, $questionId)
    {
        $question = $this->questionRepository->getById($questionId);
        $exam = $this->examRepository->getById($examId);
        $typeValues = property_exists($this, $exam->question_type.'Repository') ? $this->{$exam->question_type.'Repository'}->getAll() : null;
        return view('dashboard.site.questions.edit', [
            'exam' => $question->exam,
            'typeValues' => $typeValues,
            'question' => $question
        ]);
    }

    public function update(QuestionRequest $request, $examId, $questionId)
    {
        return $this->question->update($request, $examId, $questionId);
    }

    public function destroy($examId, $questionId)
    {
        return $this->question->destroy($questionId);
    }
}
